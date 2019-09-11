<?php
namespace Kna\Payum\YandexCheckout;


use Kna\Payum\YandexCheckout\Action\Api\CancelPaymentAction;
use Kna\Payum\YandexCheckout\Action\Api\CapturePaymentAction;
use Kna\Payum\YandexCheckout\Action\Api\CreatePaymentAction;
use Kna\Payum\YandexCheckout\Action\Api\CreateRefundAction;
use Kna\Payum\YandexCheckout\Action\Api\GetPaymentInfoAction;
use Kna\Payum\YandexCheckout\Action\AuthorizeAction;
use Kna\Payum\YandexCheckout\Action\CancelAction;
use Kna\Payum\YandexCheckout\Action\ConvertDetailsToYandexPaymentAction;
use Kna\Payum\YandexCheckout\Action\ConvertPaymentToDetailsAction;
use Kna\Payum\YandexCheckout\Action\CaptureAction;
use Kna\Payum\YandexCheckout\Action\ConvertPaymentToYandexPaymentAction;
use Kna\Payum\YandexCheckout\Action\NotifyAction;
use Kna\Payum\YandexCheckout\Action\NotifyPaymentAction;
use Kna\Payum\YandexCheckout\Action\RefundAction;
use Kna\Payum\YandexCheckout\Action\RefundPaymentAction;
use Kna\Payum\YandexCheckout\Action\StatusAction;
use Kna\Payum\YandexCheckout\Action\SyncAction;
use Kna\Payum\YandexCheckout\Action\SyncPaymentAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use YandexCheckout\Client;

class YandexCheckoutGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'yandex_checkout',
            'payum.factory_title' => 'yandex_checkout',
            'payum.action.refund_payment' => new RefundPaymentAction(),
            'payum.action.sync_payment' => new SyncPaymentAction(),
            'payum.action.notify_payment' => new NotifyPaymentAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.convert_payment_to_details' => new ConvertPaymentToDetailsAction(),
            'payum.action.convert_payment_to_yandex_payment' => new ConvertPaymentToYandexPaymentAction(),
            'payum.action.convert_details_to_yandex_payment' => new ConvertDetailsToYandexPaymentAction(),
            'payum.action.api.get_payment_info' => new GetPaymentInfoAction(),
            'payum.action.api.create_payment' => new CreatePaymentAction(),
            'payum.action.api.capture_payment' => new CapturePaymentAction(),
            'payum.action.api.cancel_payment' => new CancelPaymentAction(),
            'payum.action.api.create_refund' => new CreateRefundAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
                'payment_id_key' => 'payment_id',
                'force_payment_id' => true
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'shop_id',
                'secret_key'
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);
                $client = new Client();
                $client->setAuth($config['shop_id'], $config['secret_key']);
                $api = new Api($client);
                $api->setPaymentIdKey($config['payment_id_key']);
                $api->setForcePaymentId($config['force_payment_id']);
                return $api;
            };
        }
    }
}
