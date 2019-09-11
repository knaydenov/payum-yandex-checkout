<?php


namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Api;
use Payum\Core\Exception\UnsupportedApiException;

trait ApiAwareTrait
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (!$api instanceof Api) {
            throw new UnsupportedApiException(sprintf('Not supported api given. It must be an instance of %s', Api::class));
        }

        $this->api = $api;
    }
}