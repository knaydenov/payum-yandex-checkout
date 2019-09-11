<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use YandexCheckout\Request\Refunds\CreateRefundRequestInterface;

class CreateRefund extends BaseRefundRequest
{
    /**
     * @var CreateRefundRequestInterface
     */
    protected $createRefundRequest;

    public function __construct(CreateRefundRequestInterface $createRefundRequest, ?string $idempotenceKey = null)
    {
        $this->createRefundRequest = $createRefundRequest;
        $this->idempotenceKey = $idempotenceKey;
    }

    /**
     * @return CreateRefundRequestInterface
     */
    public function getCreateRefundRequest(): CreateRefundRequestInterface
    {
        return $this->createRefundRequest;
    }
}