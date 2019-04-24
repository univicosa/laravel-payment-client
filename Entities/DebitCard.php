<?php

namespace Payments\Client\Entities;

use Carbon\Carbon;

class DebitCard extends PaymentAbstract implements \JsonSerializable
{
    /**
     * @var string
     */
    private $uri = 'api/debit';

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
     * @var string
     */
    private $callback;

    /**
     * @param string $number
     * @param string $holder
     * @param string $brand
     * @param string $cvv
     * @param string $expiration
     * @param string $callback
     */
    public function __construct(
        string $number,
        string $holder,
        string $brand,
        string $cvv,
        string $expiration,
        string $callback
    ) {
        $this->number = $number;
        $this->holder = $holder;
        $this->brand = $brand;
        $this->cvv = $cvv;
        $this->expiration = Carbon::createFromFormat('m/Y', $expiration);
        $this->callback = $callback;
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
    public function jsonSerialize()
    {
        $debit_card = [
            'cvv' => $this->cvv,
            'brand' => $this->brand,
            'number' => $this->number,
            'holder' => $this->holder,
            'expiration' => $this->expiration->format('m/Y'),
            'callback' => $this->callback
        ];

        return array_merge(compact('debit_card'), $this->payment->jsonSerialize());
    }
}