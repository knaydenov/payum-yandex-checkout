<?php


namespace Kna\Payum\YandexCheckout\Request\Api;



use YandexCheckout\Model\Payment;
use YandexCheckout\Model\PaymentInterface;

interface PaymentAwareInterface
{
    /**
     * @return Payment
     */
    public function getPayment(): Payment;

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment): void;
}