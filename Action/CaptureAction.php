<?php
namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Request\Api\CapturePayment;
use Kna\Payum\YandexCheckout\Request\Api\GetPaymentInfo;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Kna\Payum\YandexCheckout\Request\Capture as ApiCapture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHumanStatus;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequest;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequestInterface;

class CaptureAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($getPaymentInfo = new GetPaymentInfo($model['payment']['id']));
        $model['payment'] = $getPaymentInfo->getPayment()->jsonSerialize();

        $this->gateway->execute($status = new GetHumanStatus($model));

        if ($status->isAuthorized()) {
            $createCaptureRequest = ($request instanceof ApiCapture && $request->getCreateCaptureRequest())
                ? $request->getCreateCaptureRequest()
                : $this->getDefaultCreateCaptureRequest($model)
            ;

            $createCaptureRequest->validate();

            $idempotenceKey = ($request instanceof ApiCapture) ? $request->getIdempotenceKey() : null;

            $this->gateway->execute($capturePayment = new CapturePayment($model['payment']['id'], $createCaptureRequest, $idempotenceKey));
            $model['payment'] = $capturePayment->getPayment()->jsonSerialize();
        } else {
            throw new \RuntimeException('Payment must be AUTHORIZED');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    /**
     * @param ArrayObject $model
     * @return CreateCaptureRequest|CreateCaptureRequestInterface
     */
    protected function getDefaultCreateCaptureRequest(ArrayObject $model): CreateCaptureRequest
    {
        $builder = CreateCaptureRequest::builder();

        $builder
            ->setAmount($model['amount'])
        ;

        return $builder->build();
    }
}
