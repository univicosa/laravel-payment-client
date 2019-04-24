<?php

namespace Payments\Client\Entities;

/**
 * @author Guilherme Nogueira <guilhermenogueira@univicosa.com.br>
 */
abstract class PaymentAbstract
{
    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @param Payment $payment
     * @return PaymentAbstract
     */
    public function setPayment(Payment $payment) : self
    {
        $this->payment = $payment;

        return $this;
    }
}