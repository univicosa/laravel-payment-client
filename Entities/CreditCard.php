<?php

namespace Payments\Client\Entities;

class CreditCard extends PaymentAbstract implements \JsonSerializable
{
    /**
     * @var string
     */
    private $uri = 'api/credit';

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $installments;

    /**
     * @param string $token
     * @param int $installments
     */
    public function __construct(string $token, int $installments = 1)
    {
        $this->token = $token;
        $this->installments = $installments;
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
        $credit_card = [
            'token' => $this->token
        ];

        $installments = $this->installments;

        return array_merge(compact('credit_card', 'installments'), $this->payment->jsonSerialize());
    }
}