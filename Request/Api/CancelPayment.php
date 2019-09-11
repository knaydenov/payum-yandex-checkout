<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


use Payum\Core\Request\Generic;

class CancelPayment extends BasePaymentRequest
{
    /**
     * @var string
     */
    protected $id;

    public function __construct(string $id, ?string $idempotenceKey = null)
    {
        $this->id = $id;
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
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}