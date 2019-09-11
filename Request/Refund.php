<?php
namespace Kna\Payum\YandexCheckout\Request;


use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareInterface;
use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareTrait;
use Payum\Core\Request\Refund as BaseRefund;
use YandexCheckout\Request\Refunds\CreateRefundRequest;

class Refund extends BaseRefund implements IdempotenceKeyAwareInterface
{
    use IdempotenceKeyAwareTrait;

    /**
     * @var CreateRefundRequest|null
     */
    protected $createRefundRequest;

    public function __construct($model, ?CreateRefundRequest $createRefundRequest = null, ?string $idempotenceKey = null)
    {
        parent::__construct($model);
        $this->createRefundRequest = $createRefundRequest;
        $this->idempotenceKey = $idempotenceKey;
    }

    /**
     * @return CreateRefundRequest|null
     */
    public function getCreateRefundRequest(): ?CreateRefundRequest
    {
        return $this->createRefundRequest;
    }

    /**
     * @param CreateRefundRequest|null $createRefundRequest
     */
    public function setCreateRefundRequest(?CreateRefundRequest $createRefundRequest): void
    {
        $this->createRefundRequest = $createRefundRequest;
    }
}