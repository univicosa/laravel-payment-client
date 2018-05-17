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
    private $number;

    /**
     * @var string
     */
    private $holder;

    /**
     * @var string
     */
    private $brand;

    /**
     * @var string
     */
    private $cvv;

    /**
     * @var Carbon
     */
    private $expiration;

    /**
     * @var int
     */
    private $installments;

    /**
     * CreditCard constructor.
     * @param string $number
     * @param string $holder
     * @param string $brand
     * @param string $cvv
     * @param string $expiration
     * @param int $installments
     */
    public function __construct(string $number,
                                string $holder,
                                string $brand,
                                string $cvv,
                                string $expiration,
                                int $installments = 1)
    {
        $this->number = $number;
        $this->holder = $holder;
        $this->brand = $brand;
        $this->cvv = $cvv;
        $this->expiration = Carbon::createFromFormat('m/Y', $expiration);
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
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $credit_card = [
            'cvv' => $this->cvv,
            'brand' => $this->brand,
            'number' => $this->number,
            'holder' => $this->holder,
            'expiration' => $this->expiration->format('m/Y')
        ];
        $installments = $this->installments;

        return array_merge(compact('credit_card', 'installments'), $this->payment->jsonSerialize());
    }
}