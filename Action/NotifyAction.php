<?php
namespace Kna\Payum\YandexCheckout\Action;


use Kna\Payum\YandexCheckout\Reply\NotificationUnknown;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Notify;
use YandexCheckout\Model\Notification\AbstractNotification;
use YandexCheckout\Model\Notification\NotificationCanceled;
use YandexCheckout\Model\Notification\NotificationRefundSucceeded;
use YandexCheckout\Model\Notification\NotificationSucceeded;
use YandexCheckout\Model\Notification\NotificationWaitingForCapture;
use Kna\Payum\YandexCheckout\Reply\NotificationWaitingForCapture as NotificationWaitingForCaptureReply;
use Kna\Payum\YandexCheckout\Reply\NotificationSucceeded as NotificationSucceededReply;
use Kna\Payum\YandexCheckout\Reply\NotificationCanceled as NotificationCanceledReply;
use Kna\Payum\YandexCheckout\Reply\NotificationRefundSucceeded as NotificationRefundSucceededReply;

class NotifyAction implements ActionInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        if ($request->getModel() instanceof NotificationWaitingForCapture) {
            throw new NotificationWaitingForCaptureReply($request->getModel());
        } elseif ($request->getModel() instanceof NotificationSucceeded) {
            throw new NotificationSucceededReply($request->getModel());
        } elseif ($request->getModel() instanceof NotificationCanceled) {
            throw new NotificationCanceledReply($request->getModel());
        } elseif ($request->getModel() instanceof NotificationRefundSucceeded) {
            throw new NotificationRefundSucceededReply($request->getModel());
        } else {
            throw new NotificationUnknown($request->getModel());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof AbstractNotification
        ;
    }
}
