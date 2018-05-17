<?php

namespace Payments\Client\Entities;


class Detail implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $item;

    /**
     * @var float
     */
    protected $value;

    /**
     * Detail constructor.
     * @param string $item
     * @param float $value
     */
    public function __construct(string $item, float $value)
    {
        $this->item = $item;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        $item = $this->item;
        $value = $this->value;

        return compact('item', 'value');
    }
}