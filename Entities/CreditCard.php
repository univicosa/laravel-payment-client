<?php

namespace Payments\Client\Entities;

use Carbon\Carbon;

class CreditCard implements \JsonSerializable
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $installments;

    /**
     * CreditCard constructor.
     * @param string $token
     */
    public function __construct(string $token,int $installments = 1)
    {
        $this->token = $token;
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
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getInstallments(): int
    {
        return $this->installments;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $credit_card = [
            'token' => $this->token
        ];

        $installments = $this->installments;

        return array_merge(compact('credit_card', 'installments'), $this->payment->jsonSerialize());
    }
}