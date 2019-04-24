<?php

namespace Payments\Client\Entities;

class Boleto extends PaymentAbstract implements \JsonSerializable
{
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

    /**
     * @param string $description
     * @return Boleto
     */
    public function addDescription(string $description) : self
    {
        $this->descriptions[] = compact('description');

        return $this;
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

        return array_merge(
            compact('descriptions', 'deadline'),
            $this->payment->jsonSerialize()
        );
    }
}