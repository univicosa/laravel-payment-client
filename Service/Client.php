<?php

namespace Payments\Client\Service;

use Illuminate\Http\JsonResponse;
use Payments\Client\Entities\Beneficiary;
use Payments\Client\Entities\Boleto;
use Payments\Client\Entities\CreditCard;
use Payments\Client\Entities\DebitCard;
use Payments\Client\Entities\Free;
use Payments\Client\Entities\PaymentAbstract;
use Payments\Client\Entities\Presential;

class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

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

            return json_decode($result->getBody(), true);
        } catch (\Exception $exception) {
            return [
                'message' => json_decode($exception->getResponse()->getBody()->getContents())->message,
                'code' => $exception->getCode()
            ];
        }
    }

    /**
     * @param string $type
     * @param string $id
     * @return array
     */
    public function cancel(string $type, string $id) : array
    {
        try {
            $types = ['boleto', 'credit', 'debit', 'presential', 'free'];
            if (!in_array($type, $types)) {
                throw new \Exception(sprintf(
                    'Tipo de cancelamento não suportado. Os tipos suportados são: %s', implode(', ', $types)
                ));
            }

            $result = $this->client->delete("api/{$type}/{$id}");

            return json_decode($result->getBody(), true);
        } catch (\Exception $exception) {
            return [
                'message' => json_decode($exception->getResponse()->getBody()->getContents())->message,
                'code' => $exception->getCode()
            ];
        }
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
            'cpf' => $user['cpf']
        ];
    }

    /**
     * @param \JsonSerializable $payment
     * @param array $payer
     * @return JsonResponse
     * @throws \Exception
     */
    public function send(\JsonSerializable $payment, array $payer = []) : JsonResponse
    {
        ini_set('max_execution_time', 720);

        $payer = empty($payer) ? $this->getPayer() : $payer;

        if (! $payment instanceof PaymentAbstract) {
            throw new \Exception('Tipo não reconhecido.', 400);
        }

        if ($payment instanceof Presential) {
            $payer = $payment->getPayer();
        }

        try{
            $form_params = array_merge(compact('payer'), $payment->jsonSerialize());
            $result = $this->client->post($payment->getUri(), compact('form_params'));

            return \response()->json(json_decode($result->getBody()));
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->getContents(), true);

            return response()->json(
                ['message' => $response['errors'] ?? $response['message']],
                $exception->getCode() === 0 ? 400 : $exception->getCode()
            );
        }
    }
}
