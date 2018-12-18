<?php

namespace Payments\Client\Service;

use Illuminate\Http\JsonResponse;
use Payments\Client\Entities\Beneficiary;
use Payments\Client\Entities\Boleto;
use Payments\Client\Entities\CreditCard;
use Payments\Client\Entities\DebitCard;
use Payments\Client\Entities\Free;
use Payments\Client\Entities\Presential;

class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => config('payment.server'),
            'headers' => [
                'Accept' => 'application/json',
                'system' => config('payment.system'),
                'password' => config('payment.password')
            ]
        ]);
    }

    /**
     * @param Beneficiary $beneficiary
     * @return array
     */
    public function createBeneficiary(Beneficiary $beneficiary) : array
    {
        try {

            $result = $this->client->post('api/beneficiary', ['form_params' => $beneficiary->jsonSerialize()]);

        } catch (\Exception $exception) {
            return [
                'message' => json_decode($exception->getResponse()->getBody()->getContents())->message,
                'code' => $exception->getCode()
            ];
        }

        return json_decode($result->getBody(), true);
    }

    /**
     * @param string $type
     * @param string $id
     * @return array
     */
    public function cancel(string $type, string $id) : array
    {
        try {

            if (!in_array($type, ['boleto', 'credit', 'debit', 'presential'])) {
                throw new \Exception('Tipo não suportado.');
            }

            $result = $this->client->delete("api/$type/$id");

        } catch (\Exception $exception) {
            return [
                'message' => json_decode($exception->getResponse()->getBody()->getContents())->message,
                'code' => $exception->getCode()
            ];
        }

        return json_decode($result->getBody(), true);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getPayer() : array
    {
        $client = \OpenId::getClient();
        $result = $client->get('api/user');
        $user = (\GuzzleHttp\json_decode($result->getBody(), true))['user'];
        if (!isset($user['address'])) {
            throw new \Exception('Endereço não preenchido.', 422);
        }

        return [
            'name' => $user['name'],
            'email' => $user['email'],
            'address' => $user['address']['street'],
            'district' => $user['address']['district'],
            'cep' => $user['address']['zip'],
            'state' => $user['address']['state']['initials'],
            'city' => $user['address']['city'],
            'cpf' => $user['cpf']];
    }

    /**
     * @param \JsonSerializable $payment
     * @return JsonResponse
     * @throws \Exception
     */
    public function send(\JsonSerializable $payment) : JsonResponse
    {
        if ($payment instanceof Presential) {
            $uri = 'api/presential';
            $payer = $payment->getPayer();
        } else {
            $payer = $this->getPayer();
            if ($payment instanceof Boleto) {
                $uri = 'api/boleto';
            } elseif ($payment instanceof CreditCard) {
                $uri = 'api/credit';
            } elseif ($payment instanceof Free) {
                $uri = 'api/free';
            } else {
                throw new \Exception('Tipo não reconhecido.', 400);
            }
        }
        try{
            $form_params = array_merge(compact('payer'), $payment->jsonSerialize());
            $result = $this->client->post($uri, compact('form_params'));

            return \response()->json(json_decode($result->getBody()));
        }catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents(), true);

            return response()->json(
                [
                    'message' => $responseBodyAsString['errors']
                ],
                $exception->getCode() === 0 ? 400 : $exception->getCode()
            );
        }
    }
}