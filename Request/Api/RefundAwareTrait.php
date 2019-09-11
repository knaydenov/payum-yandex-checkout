<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use YandexCheckout\Model\Refund;

trait RefundAwareTrait
{
    /**
     * @var Refund
     */
    protected $refund;

    /**
     * {@inheritDoc}
     */
    public function getRefund(): Refund
    {
        return $this->refund;
    }

    /**
     * {@inheritDoc}
     */
    public function setRefund(Refund $refund): void
    {
        $this->refund = $refund;
    }
}