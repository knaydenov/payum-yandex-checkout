<?php
namespace Kna\Payum\YandexCheckout\Action;


use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Model\Payment as YandexPayment;

class StatusAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @param GetStatusInterface $request
     * @throws \Exception
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (!$model->offsetExists('payment')) {
            $request->markNew();
            return;
        }
        $request->markUnknown();

        $this->gateway->execute($convert = new Convert($model, YandexPayment::class));
        /** @var YandexPayment $yandexPayment */
        $yandexPayment = $convert->getResult();

        if ($yandexPayment->getStatus() === PaymentStatus::PENDING) {
            $request->markPending();
        }
        if ($yandexPayment->getStatus() === PaymentStatus::WAITING_FOR_CAPTURE) {
            $request->markAuthorized();
        }
        if ($yandexPayment->getStatus() === PaymentStatus::SUCCEEDED) {
            $request->markCaptured();
        }
        if ($yandexPayment->getStatus() === PaymentStatus::CANCELED) {
            $request->markCanceled();
        }
        if ($yandexPayment->getRefundedAmount() && $yandexPayment->getRefundedAmount()->getValue() === $yandexPayment->getAmount()->getValue()) {
            $request->markRefunded();
        }
        if (
            ($yandexPayment->getStatus() === PaymentStatus::PENDING || $yandexPayment->getStatus() === PaymentStatus::WAITING_FOR_CAPTURE) &&
            $yandexPayment->getExpiresAt() && $yandexPayment->getExpiresAt() < (new \DateTime())
        ) {
            $request->markExpired();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetHumanStatus &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
