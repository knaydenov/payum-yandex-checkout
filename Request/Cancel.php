<?php
namespace Kna\Payum\YandexCheckout\Request;


use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareInterface;
use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareTrait;
use Payum\Core\Request\Cancel as BaseCancel;

class Cancel extends BaseCancel implements IdempotenceKeyAwareInterface
{
    use IdempotenceKeyAwareTrait;

    public function __construct($model, ?string $idempotenceKey = null)
    {
        parent::__construct($model);
        $this->idempotenceKey = $idempotenceKey;
    }
}