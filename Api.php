<?php
namespace Kna\Payum\YandexCheckout;


use YandexCheckout\Client;

class Api
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var string
     */
    protected $paymentIdKey;

    /**
     * @var bool
     */
    protected $forcePaymentId;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getPaymentIdKey(): string
    {
        return $this->paymentIdKey;
    }

    /**
     * @param string $paymentIdKey
     */
    public function setPaymentIdKey(string $paymentIdKey): void
    {
        $this->paymentIdKey = $paymentIdKey;
    }

    /**
     * @return bool
     */
    public function shouldForcePaymentId(): bool
    {
        return $this->forcePaymentId;
    }

    /**
     * @param bool $forcePaymentId
     */
    public function setForcePaymentId(bool $forcePaymentId): void
    {
        $this->forcePaymentId = $forcePaymentId;
    }
}