HtcNowTaxiBundle
============

This bundle provides integration with nowtaxi.ru order exchange API (http://doc.nowtaxi.ru/api/exchange) in a Symfony 2 application.

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
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Htc\NowTaxiBundle\HtcNowTaxiBundle(),
        );

        // ...
    }

    // ...
}
```

## Documentation

For documentation, see:
```
Resources/doc/
```
[Read the documentation](https://github.com/HighTechnologiesCenter/htc-nowtaxi-bundle/blob/master/Resources/docs/index.md)
