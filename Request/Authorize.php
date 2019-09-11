<?php
namespace Kna\Payum\YandexCheckout\Request;


use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareInterface;
use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareTrait;
use Payum\Core\Request\Authorize as BaseAuthorize;
use YandexCheckout\Request\Payments\CreatePaymentRequest;

class Authorize extends BaseAuthorize implements IdempotenceKeyAwareInterface
{
    use IdempotenceKeyAwareTrait;

    /**
     * @var CreatePaymentRequest|null
     */
    protected $createPaymentRequest;

    public function __construct($model, ?CreatePaymentRequest $createPaymentRequest = null, ?string $idempotenceKey = null)
    {
        parent::__construct($model);
        $this->createPaymentRequest = $createPaymentRequest;
        $this->idempotenceKey = $idempotenceKey;
    }

    /**
     * @return CreatePaymentRequest|null
     */
    public function getCreatePaymentRequest(): ?CreatePaymentRequest
    {
        return $this->createPaymentRequest;
    }

    /**
     * @param CreatePaymentRequest|null $createPaymentRequest
     */
    public function setCreatePaymentRequest(?CreatePaymentRequest $createPaymentRequest): void
    {
        $this->createPaymentRequest = $createPaymentRequest;
    }
}