<?php


namespace Kna\Payum\YandexCheckout\Action\Api;


use Kna\Payum\YandexCheckout\Reply\ApiRequestFailed;
use Kna\Payum\YandexCheckout\Request\Api\CreatePayment;
use Payum\Core\Exception\RequestNotSupportedException;
use YandexCheckout\Common\Exceptions\ApiException;

class CreatePaymentAction extends BaseApiAwareAction
{

    /**
     * @param CreatePayment $request
     * @throws \Exception
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        try {
            $yandexPayment = $this->api->getClient()->createPayment($request->getCreatePaymentRequest(), $request->getIdempotenceKey());
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
            $request instanceof CreatePayment
        ;
    }
}