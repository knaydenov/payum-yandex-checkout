<?php
namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Request\Api\CreateRefund;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Refund;
use Kna\Payum\YandexCheckout\Request\Refund as ApiRefund;
use YandexCheckout\Request\Refunds\CreateRefundRequest;
use YandexCheckout\Request\Refunds\CreateRefundRequestInterface;

class RefundAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use ApiAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Refund $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($status = new GetHumanStatus($model));


        if ($status->isCaptured()) {
            $createRefundRequest = ($request instanceof ApiRefund && $request->getCreateRefundRequest())
                ? $request->getCreateRefundRequest()
                : $this->getDefaultCreateRefundRequest($model)
            ;

            if ($this->api->shouldForcePaymentId()) {
                $createRefundRequest->setPaymentId($model['payment']['id']);
            }

            $createRefundRequest->validate();

            $idempotenceKey = ($request instanceof ApiRefund) ? $request->getIdempotenceKey() : null;

            $this->gateway->execute($createRefund = new CreateRefund($createRefundRequest, $idempotenceKey));

            $refunds = $model['refunds'];
            $refunds[$createRefund->getRefund()->getId()] = $createRefund->getRefund()->jsonSerialize();
            $model->replace([
                'refunds' => $refunds
            ]);

        } else {
            throw new \RuntimeException('Payment must be CAPTURED');
        }

    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Refund &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    /**
     * @param ArrayObject $model
     * @return CreateRefundRequest|CreateRefundRequestInterface
     */
    protected function getDefaultCreateRefundRequest(ArrayObject $model): CreateRefundRequest
    {
        $builder = CreateRefundRequest::builder();
        $builder
            ->setPaymentId($model['payment']['id'])
            ->setAmount($model['payment']['amount'])
        ;
        return $builder->build();
    }
}
