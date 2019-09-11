# Payum Yandex.Checkout gateway

The Payum extension. It provides [Yandex.Checkout](https://kassa.yandex.ru/developers/api) integration.

## Installation

```shell script
composer require kna/payum-yandex-checkout
```

## Configuring

```php
use Payum\Core\GatewayFactoryInterface;
use Kna\Payum\YandexCheckout\YandexCheckoutGatewayFactory;

$payumBuilder->addGatewayFactory('yandex_checkout', function(array $config, GatewayFactoryInterface $gatewayFactory) {
    return new YandexCheckoutGatewayFactory($config, $gatewayFactory);
});

$payumBuilder->addGateway('yandex_checkout', [
    'factory' => 'yandex_checkout',
    'shop_id' => '<shop_id>', 
    'secret_key' => '<secret_key>',
    'payment_id_key' => 'payment_id', // optional
    'force_payment_id' => true // optional
]);
``` 

## Symfony integration


### PayumBundle installation

In order to use that extension with the Symfony, you will need to install [PayumBundle](https://github.com/Payum/PayumBundle) first and configure it according to its documentation.

```bash
composer require payum/payum-bundle
```

### Register YandexCheckoutGatewayFactory as a service

```yaml
# app/config/services.yml

services:
  app.payum.yandex_checkout_factory:
    class: Payum\Core\Bridge\Symfony\Builder\GatewayFactoryBuilder
    arguments:
    - 'Kna\Payum\YandexCheckout\YandexCheckoutGatewayFactory'
    tags:
    - { name: payum.gateway_factory_builder, factory: yandex_checkout }
```

### Configure the gateway

```yaml
# app/config/config.yml

payum:
  gateways:
    yandex_checkout:
      factory: yandex_checkout
      shop_id: '<shop_id>'
      secret_key: '<secret_key>'
      payment_id_key: 'payment_id' # optional
      force_payment_id: true # optional
```

### Gateway usage

Retrieve it from the `payum` service:

```php
$gateway = $this->get('payum')->getGeteway('yandex_checkout');
```