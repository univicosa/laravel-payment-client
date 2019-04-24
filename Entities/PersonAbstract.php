<?php

namespace Payments\Client\Entities;

/**
 * @author Guilherme Nogueira <guilhermenogueira@univicosa.com.br>
 */
abstract class PersonAbstract implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @param string $name
     * @param string $email
     * @param string $cpf
     */
    public function __construct(
        string $name,
        string $email,
        string $cpf
    ) {
        $this->name = trim($name);
        $this->email = trim($email);
        $this->cpf = $cpf;
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
        $email = $this->email;
        $cpf =  $this->cpf;

        return compact('name', 'email', 'cpf');
    }
}