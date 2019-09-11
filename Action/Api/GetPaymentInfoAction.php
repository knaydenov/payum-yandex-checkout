<?php


namespace Kna\Payum\YandexCheckout\Action\Api;


use Kna\Payum\YandexCheckout\Reply\ApiRequestFailed;
use Kna\Payum\YandexCheckout\Request\Api\GetPaymentInfo;
use Payum\Core\Exception\RequestNotSupportedException;
use YandexCheckout\Common\Exceptions\ApiException;

class GetPaymentInfoAction extends BaseApiAwareAction
{

    /**
     * @param GetPaymentInfo $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        try {
            $yandexPayment = $this->api->getClient()->getPaymentInfo($request->getId());
            $request->setPayment($yandexPayment);
        } catch (ApiException $exception) {
            throw new ApiRequestFailed($exception);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetPaymentInfo
        ;
    }
}