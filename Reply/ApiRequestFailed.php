<?php
namespace Kna\Payum\YandexCheckout\Reply;


use YandexCheckout\Common\Exceptions\ApiException;

class ApiRequestFailed extends \LogicException
{
    /**
     * @var ApiException
     */
    protected $apiException;

    public function __construct(ApiException $apiException)
    {
        $this->apiException = $apiException;
        parent::__construct($apiException->getMessage(), $apiException->getCode(), $apiException->getPrevious());
    }
}