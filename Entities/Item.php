<?php

namespace Payments\Client\Entities;


class Item implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $discount_amount;

    /**
     * @var float
     */
    private $final_value;

    /**
     * @var array
     */
    private $discounts;

    /**
     * @var string
     */
    private $beneficiary;

    /**
     * @var array
     */
    private $details;

    /**
     * @param string $name
     * @param float $amount
     * @param float $discount_amount
     * @param float $final_value
     * @param string $beneficiary
     */
    public function __construct(
        string $name,
        float $amount,
        float $discount_amount,
        float $final_value,
        string $beneficiary
    ) {
        $this->name = $name;
        $this->amount = $amount;
        $this->discount_amount = $discount_amount;
        $this->final_value = $final_value;
        $this->discounts = [];
        $this->beneficiary = $beneficiary;
        $this->details = [];
    }

    /**
     * @param Detail $detail
     * @return Item
     */
    public function addDetail(Detail $detail) : self
    {
        $this->details[] = $detail;

        return $this;
    }

    /**
     * @param string $key
     * @param float $value
     * @return Item
     */
    public function addDiscount(string $key, float $value) : self
    {
        $this->discounts[$key] = $value;

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
        $name = $this->name;
        $amount = $this->amount;
        $discount_amount = $this->discount_amount;
        $final_value = $this->final_value;
        $discounts = $this->discounts;
        $beneficiary = $this->beneficiary;

        $details = [];

        /** @var Detail $detail */
        foreach ($this->details  as $detail) {
            $details[] = $detail->jsonSerialize();
        }

        $result = compact('name', 'amount', 'discount_amount', 'final_value', 'beneficiary', 'details');

        if (!empty($discounts)) {
            $result = array_merge($result, compact('discounts'));
        }

        return $result;
    }
}