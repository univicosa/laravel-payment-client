<?php

namespace Payments\Client\Entities;

class Presential extends PaymentAbstract implements \JsonSerializable
{
    /**
     * @var string
     */
    private $uri = 'api/presential';

    /**
     * @var string
     */
    const MONEY = 'money';

    /**
     * @var string
     */
    const CREDIT = 'credit_card';

    /**
     * @var string
     */
    const DEBIT = 'debit_card';

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $installments;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Payer
     */
    private $payer;

    /**
     * @var Responsible
     */
    private $responsible;

    /**
     * @param string $type
     * @param int $installments
     */
    public function __construct(string $type, int $installments = 1, $token = null)
    {
        $this->type = $type;
        $this->installments = $installments;
        $this->token = $token;
    }

    /**
     * @param Payer $payer
     * @return Presential
     */
    public function setPayer(Payer $payer) : self
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * @param Responsible $responsible
     * @return Presential
     */
    public function setResponsible(Responsible $responsible) : self
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * @return array
     */
    public function getPayer() : array
    {
        return $this->payer->jsonSerialize();
    }

    /**
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() : array
    {
        $presential = [
            'token' => $this->token,
            'type' => $this->type,
            'installments' => $this->installments,
            'responsible' => $this->responsible->jsonSerialize()
        ];

        return array_merge(compact('presential'), $this->payment->jsonSerialize());
    }
}