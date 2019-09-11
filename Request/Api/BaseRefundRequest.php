<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use Payum\Core\Request\Generic;
use Payum\Core\Request\GetStatusInterface;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Model\Refund;
use YandexCheckout\Model\RefundInterface;
use YandexCheckout\Request\Payments\PaymentResponse;
use YandexCheckout\Request\Refunds\RefundResponse;

class BaseRefundRequest implements RefundAwareInterface, IdempotenceKeyAwareInterface
{
    use RefundAwareTrait;
    use IdempotenceKeyAwareTrait;
}