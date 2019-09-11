<?php


namespace Kna\Payum\YandexCheckout\Action\Api;


use Kna\Payum\YandexCheckout\Reply\ApiRequestFailed;
use Kna\Payum\YandexCheckout\Request\Api\CapturePayment;
use Payum\Core\Exception\RequestNotSupportedException;
use YandexCheckout\Common\Exceptions\ApiException;

class CapturePaymentAction extends BaseApiAwareAction
{

    /**
     * @param CapturePayment $request
     * @throws \Exception
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        try {
            $yandexPayment = $this->api->getClient()->capturePayment($request->getCreateCaptureRequest(), $request->getId(), $request->getIdempotenceKey());
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
            $request instanceof CapturePayment
        ;
    }
}