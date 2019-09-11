<?php
namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Request\Api\GetPaymentInfo;
use Kna\Payum\YandexCheckout\Request\Refund;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Sync;
use Kna\Payum\YandexCheckout\Request\Sync as ApiSync;
use YandexCheckout\Model\Payment;

class SyncAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Sync $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($status = new GetHumanStatus($model));

        if ($request instanceof ApiSync && $request->getReplace() instanceof Payment) {
            $model['payment'] = $request->getReplace()->jsonSerialize();
        } elseif ($request instanceof ApiSync && $request->getReplace() instanceof Refund) {
            $refunds = $model['refunds'];
            $refunds[$request->getReplace()->getId()] = $request->getReplace()->jsonSerialize();
            $model->replace([
                'refunds' => $refunds
            ]);
        } elseif (!$status->isNew()) {
            $this->gateway->execute($getPaymentInfo = new GetPaymentInfo($model['payment']['id']));
            $model['payment'] = $getPaymentInfo->getPayment()->jsonSerialize();
        } else {
            throw new \RuntimeException('Can not sync new payment!');
        }

    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Sync &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }

}