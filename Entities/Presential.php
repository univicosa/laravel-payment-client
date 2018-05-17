<?php

namespace Payments\Client\Entities;

class Presential implements \JsonSerializable
{
    const MONEY = 'money';

    const CREDIT = 'credit_card';

    const DEBIT = 'debit_card';

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $installments;

    /**
     * @var Payer
     */
    private $payer;

    /**
     * @var Responsible
     */
    private $responsible;

    /**
     * Presential constructor.
     * @param string $type
     * @param int $installments
     */
    public function __construct(string $type, int $installments = 1)
    {
        $this->type = $type;
        $this->installments = $installments;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param Payer $payer
     */
    public function setPayer(Payer $payer)
    {
        $this->payer = $payer;
    }

    /**
     * @return array
     */
    public function getPayer() : array
    {
        return $this->payer->jsonSerialize();
    }

    /**
     * @param Responsible $responsible
     */
    public function setResponsible(Responsible $responsible)
    {
        $this->responsible = $responsible;
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
            'type' => $this->type,
            'installments' => $this->installments,
            'responsible' => $this->responsible->jsonSerialize()
        ];

        return array_merge(compact('presential'), $this->payment->jsonSerialize());
    }
}