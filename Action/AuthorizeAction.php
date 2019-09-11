<?php
namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Request\Api\CreatePayment;
use Kna\Payum\YandexCheckout\Request\Api\GetPaymentInfo;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Authorize;
use Kna\Payum\YandexCheckout\Request\Authorize as ApiAuthorize;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHumanStatus;
use YandexCheckout\Model\ConfirmationType;
use YandexCheckout\Model\MonetaryAmount;
use YandexCheckout\Request\Payments\CreatePaymentRequest;
use YandexCheckout\Request\Payments\CreatePaymentRequestInterface;

class AuthorizeAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use ApiAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Authorize $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($status = new GetHumanStatus($model));

        if ($status->isNew()) {
            $createPaymentRequest = ($request instanceof ApiAuthorize && $request->getCreatePaymentRequest())
                ? $request->getCreatePaymentRequest()
                : $this->getDefaultCreatePaymentRequest($model)
            ;

            $metadata = $createPaymentRequest->hasMetadata() ? $createPaymentRequest->getMetadata() : [];
            $metadata[$this->api->getPaymentIdKey()] = $model['payment_id'];
            $createPaymentRequest->setMetadata($metadata);

            $createPaymentRequest->validate();

            $idempotenceKey = ($request instanceof ApiAuthorize) ? $request->getIdempotenceKey() : null;

            $this->gateway->execute($createPayment = new CreatePayment($createPaymentRequest, $idempotenceKey));
            $model['payment'] = $createPayment->getPayment()->jsonSerialize();
            $model['confirmation_type'] = $createPayment->getPayment()->getConfirmation()->getType();

            if ($createPayment->getPayment()->getConfirmation()->getType() === ConfirmationType::REDIRECT) {
                $model['confirmation_type'] = $createPayment->getPayment()->getConfirmation()->getType();
            }

            $model['confirmation_url'] = $createPayment->getPayment()->getConfirmation()->offsetGet('confirmationUrl');
        } else {
            $this->gateway->execute($getPaymentInfo = new GetPaymentInfo($model['payment']['id']));
            $model['payment'] = $getPaymentInfo->getPayment()->jsonSerialize();
        }

        $this->gateway->execute($status = new GetHumanStatus($model));

        if ($status->isPending()) {
            if ($model['confirmation_type'] === ConfirmationType::REDIRECT) {
                throw new HttpRedirect($model['confirmation_url']);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    /**
     * @param ArrayObject $model
     * @return CreatePaymentRequest|CreatePaymentRequestInterface
     */
    protected function getDefaultCreatePaymentRequest(ArrayObject $model): CreatePaymentRequest
    {
        $builder = CreatePaymentRequest::builder();

        $model->validatedKeysSet(['return_url']);

        $builder
            ->setCapture($model->offsetExists('capture') ? $model['capture'] : false)
            ->setConfirmation([
                'type' => ConfirmationType::REDIRECT,
                'return_url' => $model['return_url']
            ])
            ->setAmount(new MonetaryAmount($model['amount'], $model['currency']))
        ;

        return $builder->build();
    }
}
