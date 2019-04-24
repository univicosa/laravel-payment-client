<?php


namespace Payments\Client\Entities;


class Payment implements \JsonSerializable
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var array
     */
    private $items;

    /**
     * Payment constructor.
     * @param float $value
     * @param string $operator
     */
    public function __construct(float $value, string $operator)
    {
        $this->value = $value;
        $this->operator = $operator;
        $this->items = [];
    }

    /**
     * @param Item $item
     * @return Payment
     */
    public function addItem(Item $item) : self
    {
        $this->items[] = $item;

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
        $value = $this->value;
        $operator = $this->operator;
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->jsonSerialize();
        }
        return compact('value', 'operator', 'items');
    }
}