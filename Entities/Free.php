<?php
/**
 * Created by Olimar Ferraz
 * Email: olimarferraz@univicosa.com.br
 * Date: 17/05/2018 - 11:46
 */

namespace Payments\Client\Entities;


class Free implements \JsonSerializable
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->payment->jsonSerialize();
    }
}