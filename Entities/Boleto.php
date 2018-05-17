<?php

namespace Payments\Client\Entities;


class Boleto implements \JsonSerializable
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var array
     */
    private $descriptions;

    /**
     * @var int
     */
    private $deadline;

    /**
     * Boleto constructor.
     * @param int $deadline
     */
    public function __construct(int $deadline = 1)
    {
        $this->deadline = $deadline;
        $this->descriptions = [];
    }

    public function addDescription(string $description)
    {
        $this->descriptions[] = compact('description');
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
    public function jsonSerialize() : array
    {
        $descriptions = $this->descriptions;
        $deadline = $this->deadline;

        return array_merge(compact('descriptions', 'deadline'), $this->payment->jsonSerialize());
    }
}