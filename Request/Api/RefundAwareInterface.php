<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use YandexCheckout\Model\Refund;
use YandexCheckout\Model\RefundInterface;

interface RefundAwareInterface
{
    /**
     * @return Refund
     */
    public function getRefund(): Refund;

    /**
     * @param Refund $refund
     */
    public function setRefund(Refund $refund): void;
}