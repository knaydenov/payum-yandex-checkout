<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


interface IdempotenceKeyAwareInterface
{
    /**
     * @return string
     */
    public function getIdempotenceKey(): string;

    /**
     * @param string $idempotenceKey
     */
    public function setIdempotenceKey(string $idempotenceKey): void;
}