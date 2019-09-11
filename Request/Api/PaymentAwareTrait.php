<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use YandexCheckout\Model\Payment;

trait PaymentAwareTrait
{
    /**
     * @var Payment
     */
    protected $payment;

    /**
     * {@inheritDoc}
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * {@inheritDoc}
     */
    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }
}