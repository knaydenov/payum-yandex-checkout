<?php
namespace Kna\Payum\YandexCheckout\Action;


use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use YandexCheckout\Model\Payment as YandexPayment;
use Payum\Core\Request\Convert;
use YandexCheckout\Request\Payments\PaymentResponse;

class ConvertDetailsToYandexPaymentAction implements ActionInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getSource());

       if (!$details->offsetExists('payment')) {
           throw new \RuntimeException('Payment not found in details');
       }

        $request->setResult(new PaymentResponse($details['payment']));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof \ArrayAccess &&
            $request->getTo() === YandexPayment::class
        ;
    }
}
