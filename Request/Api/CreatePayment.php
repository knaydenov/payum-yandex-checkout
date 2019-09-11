<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use YandexCheckout\Request\Payments\CreatePaymentRequestInterface;

class CreatePayment extends BasePaymentRequest
{
    /**
     * @var CreatePaymentRequestInterface
     */
    protected $createPaymentRequest;

    public function __construct(CreatePaymentRequestInterface $createPaymentRequest, ?string $idempotenceKey = null)
    {
        $this->createPaymentRequest = $createPaymentRequest;
        $this->idempotenceKey = $idempotenceKey;
    }

    /**
     * @return CreatePaymentRequestInterface
     */
    public function getCreatePaymentRequest(): CreatePaymentRequestInterface
    {
        return $this->createPaymentRequest;
    }
}