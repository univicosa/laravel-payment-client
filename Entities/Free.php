<?php
/**
 * Created by Olimar Ferraz
 * Email: olimarferraz@univicosa.com.br
 * Date: 17/05/2018 - 11:46
 */

namespace Payments\Client\Entities;


class Free extends PaymentAbstract implements \JsonSerializable
{
    /**
     * @var string
     */
    private $uri = 'api/free';

    /**
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->payment->jsonSerialize();
    }
}