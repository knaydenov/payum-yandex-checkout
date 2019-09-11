<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use Payum\Core\Request\Generic;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequest;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequestInterface;

class CapturePayment extends BasePaymentRequest
{
    /**
     * @var string
     */
    protected $id;

    /** @var CreateCaptureRequestInterface|null */
    protected $createCaptureRequest;

    public function __construct(string $id, ?CreateCaptureRequestInterface $createCaptureRequest = null, ?string $idempotenceKey = null)
    {
        $this->id = $id;
        $this->createCaptureRequest = $createCaptureRequest;
        $this->idempotenceKey = $idempotenceKey;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return CreateCaptureRequestInterface
     */
    public function getCreateCaptureRequest(): ?CreateCaptureRequestInterface
    {
        return $this->createCaptureRequest;
    }

}