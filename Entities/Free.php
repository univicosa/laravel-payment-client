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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->payment->jsonSerialize();
    }
}