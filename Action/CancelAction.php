<?php
namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Request\Api\CancelPayment;
use Kna\Payum\YandexCheckout\Request\Api\GetPaymentInfo;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Cancel;
use Kna\Payum\YandexCheckout\Request\Cancel as ApiCancel;
use Payum\Core\Request\GetHumanStatus;

class CancelAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Cancel $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($getPaymentInfo = new GetPaymentInfo($model['payment']['id']));
        $model['payment'] = $getPaymentInfo->getPayment()->jsonSerialize();

        $this->gateway->execute($status = new GetHumanStatus($model));

        if ($status->isPending() || $status->isAuthorized()) {

            $idempotenceKey = ($request instanceof ApiCancel) ? $request->getIdempotenceKey() : null;

            $this->gateway->execute($cancelPayment = new CancelPayment($model['payment']['id'], $idempotenceKey));
            $model['payment'] = $cancelPayment->getPayment()->jsonSerialize();
        } else {
            throw new  \RuntimeException('Payment must be in PENDING or AUTHORIZED state');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Cancel &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
