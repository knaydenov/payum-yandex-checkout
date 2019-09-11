<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use Payum\Core\Request\Generic;
use Payum\Core\Request\GetStatusInterface;
use YandexCheckout\Model\Payment;
use YandexCheckout\Model\PaymentInterface;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Request\Payments\PaymentResponse;

class BasePaymentRequest implements PaymentAwareInterface, IdempotenceKeyAwareInterface
{
    use PaymentAwareTrait;
    use IdempotenceKeyAwareTrait;
}