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
    private $valid_until;

    /**
     * Beneficiary constructor.
     * @param string $name
     * @param string $account
     * @param Carbon $validUntil
     */
    public function __construct(string $name, string $account, Carbon $valid_until)
    {
        $this->name = $name;
        $this->account = $account;
        $this->valid_until = $valid_until;
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
        $valid_until = (string) $this->valid_until;

        return compact('name', 'system', 'account', 'valid_until');
    }
}