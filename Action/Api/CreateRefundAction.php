<?php


namespace Kna\Payum\YandexCheckout\Action\Api;


use Kna\Payum\YandexCheckout\Reply\ApiRequestFailed;
use Kna\Payum\YandexCheckout\Request\Api\CreateRefund;
use Payum\Core\Exception\RequestNotSupportedException;
use YandexCheckout\Common\Exceptions\ApiException;

class CreateRefundAction extends BaseApiAwareAction
{
    /**
     * @param CreateRefund $request
     * @throws \Exception
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        try {
            $yandexRefund = $this->api->getClient()->createRefund($request->getCreateRefundRequest(), $request->getIdempotenceKey());
            $request->setRefund($yandexRefund);
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
            $request instanceof CreateRefund
        ;
    }
}