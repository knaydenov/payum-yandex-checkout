<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


trait IdempotenceKeyAwareTrait
{
    /**
     * @var string
     */
    protected $idempotenceKey;

    /**
     * @return string
     */
    public function getIdempotenceKey(): string
    {
        if (empty($this->idempotenceKey)) {
            $this->idempotenceKey = uniqid('', true);
        }

        return $this->idempotenceKey;
    }

    /**
     * @param string $idempotenceKey
     */
    public function setIdempotenceKey(string $idempotenceKey): void
    {
        $this->idempotenceKey = $idempotenceKey;
    }

}