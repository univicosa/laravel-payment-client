<?php

namespace Payments\Client\Entities;

use Carbon\Carbon;

class Beneficiary implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $system;

    /**
     * @var string
     */
    private $account;

    /**
     * @var Carbon
     */
    private $validUntil;

    /**
     * Beneficiary constructor.
     * @param string $name
     * @param string $account
     * @param Carbon $validUntil
     */
    public function __construct(string $name, string $account, Carbon $validUntil)
    {
        $this->name = $name;
        $this->account = $account;
        $this->valid_until = $validUntil;
        $this->system = config('payment.system');
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        $name = $this->name;
        $system = $this->system;
        $account = $this->account;
        $validUntil = (string) $this->validUntil;

        return compact('name', 'system', 'account', 'validUntil');
    }
}