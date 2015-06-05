HtcNowTaxiBundle
============

This bundle provides integration with [http://nowtaxi.ru](http://nowtaxi.ru) order exchange [API](http://doc.nowtaxi.ru/api/exchange) in a Symfony 2 application.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require htc/now-taxi-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new new Htc\NowTaxiBundle\HtcNowTaxiBundle(),
        );

        // ...
    }

    // ...
}
```

Import the routing definition in `routing.yml`:

```yaml
# app/config/routing.yml
htc_now_taxi:
    resource: "@HtcNowTaxiBundle/Resources/config/routing.yml"
    prefix:   /htc-nowtaxi
```

Enable the bundle's configuration in `app/config/config.yml`:

```yaml
# app/config/config.yml
htc_now_taxi:
  order_converter:
    service: acme_bundle.now_taxi.converter.order   # a service that converts order data to a NowTaxi order. 
                                                    # Needs to be implemented manually by yourself 
    throw_exceptions: false                         # If true a service `htc_now_taxi.order_management.service` will 
                                                    # throw exceptions  
  api:
    host: http://default.host                       # NowTaxi api host
    key: default_key                                # Api-key
```

Usage
-----

### Order Converter service

First you need to implement a class that converts your order data to an HtcNowTaxi order
object:

```php
<?php
// ../AcmeBundle/Service/HtcNowTaxi/OrderConverter.php
namespace AcmeBundle\Service\HtcNowTaxi;

use Htc\NowTaxiBundle\Constant\PricingAlgorithm,
    Htc\NowTaxiBundle\Constant\TariffCategory,
    Htc\NowTaxiBundle\Converter\AbstractOrderConverter,
    Htc\NowTaxiBundle\Entity\Client,
    Htc\NowTaxiBundle\Entity\Fee,
    Htc\NowTaxiBundle\Entity\Order,
    Htc\NowTaxiBundle\Entity\OrderProperties,
    Htc\NowTaxiBundle\Entity\Place,
    Htc\NowTaxiBundle\Entity\Requirements;

/**
 * Class OrderConverter
 * @package AcmeBundle\Service\HtcNowTaxi
 */
class OrderConverter extends AbstractOrderConverter
{
// ...
  
    /**
     * Converts data to NowTaxiOrder object
     *
     * @param mixed $data
     *
     * @return Order
     */
    public function convert($data)
    {
        $order = new Order();

        // ... order creation logic

        return $order;
    }
// ...

}
```

Then you need to declare it as a service:

```yaml
# AcmeBundle/Resources/config/services.yml
services:
#...
    acme_bundle.now_taxi.converter.order:
        class: AcmeBundle\Service\NowTaxi\OrderConverter
#...
```

### Events, Listeners

There is some events:
 - "htc_now_taxi.order_created" (Htc\NowTaxiBundle\Event\OrderEvent) - fires when order creation request was sent into the nowtaxi service
 - "htc_now_taxi.order_changed" (Htc\NowTaxiBundle\Event\OrderEvent) - fires when order status was changed by the nowtaxi service
 - "htc_now_taxi.order_cancelled" (Htc\NowTaxiBundle\Event\OrderEvent) - fires when order was cancelled by the nowtaxi service
 - "htc_now_taxi.drivers_position_changed" (Htc\NowTaxiBundle\Event\DriversPositionEvent) - fires when drivers position was changed
  
If you want to listen it you need to create some listeners as in the example:

```php
<?php
// ../AcmeBundle/Listener/HtcNowTaxi/OrderChangesListener.php
namespace AcmeBundle/Listener/HtcNowTaxi;

use Htc\NowTaxiBundle\Constant\OrderStatus,
    Htc\NowTaxiBundle\Entity\Order,
    Htc\NowTaxiBundle\Entity\OrderProperties,
    Htc\NowTaxiBundle\Event\OrderEvent,
    Htc\NowTaxiBundle\Event\DriversPositionEvent;

/**
 * HtcNowTaxi event listener
 *
 * Class NowTaxiEventListener
 * @package AcmeBundle\Listener\HtcNowTaxi
 */
class EventListener
{
    // ...
    
    /**
     * @param \Htc\NowTaxiBundle\Event\OrderEvent $event
     */
    public function onOrderCreated(OrderEvent $event)
    {
        // some logic here
    }

    /**
     * @param \Htc\NowTaxiBundle\Event\OrderEvent $event
     */
    public function onOrderChanged(OrderEvent $event)
    {
        // some logic here
    }
    
    /**
     * @param \Htc\NowTaxiBundle\Event\DriversPositionEvent $event
     */
    public function onDriversPositionChanged(DriversPositionEvent $event)
    {
        // some logic here
    }
    
    // ...
}
```

Then register it in services:

```yaml
# AcmeBundle/Resources/config/services.yml
services:
#...

    module.now_taxi.listener.order_changes:
        class: Module\NowTaxi\Listener\OrderChangesListener
        tags:
            - { name: kernel.event_listener, event: htc_now_taxi.order_created, method: onOrderCreated }
            - { name: kernel.event_listener, event: htc_now_taxi.order_changed, method: onOrderChanged }
            - { name: kernel.event_listener, event: htc_now_taxi.drivers_position_changed, method: onDriversPositionChanged }
          
#...
```

### When all done...

To manage orders you need to use the service `htc_now_taxi.order_management.service`:

```php
<?php

namespace AcmeBundle\Controller;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class OrderController
 * @package AcmeBundle\Controller
 */
class OrderController extends Controller
{
    /**
     * Creates an order
     * 
     * @param $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction($data)
    {
        // ... 
        
        $order = $this->get('htc_now_taxi.order_management.service')->putOrder($data);
        
        // ... 

        return new Response();
    }

    /**
     * Cancel an ordered
     * 
     * @param $orderId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelAction($orderId)
    {
        // ... 
        
        $this->get('htc_now_taxi.order_management.service')->cancelOrder($orderId, 'Order was cancelled!');
        
        // ... 
        
        return new Response(); 
    }
}
```