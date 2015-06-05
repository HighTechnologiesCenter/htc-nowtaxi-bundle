<?php

namespace Htc\NowTaxiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HtcNowTaxiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->loadOrderConverterService($config, $container);
        $this->loadApiParameters($config, $container);
    }


    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function loadOrderConverterService(array $config, ContainerBuilder $container)
    {
        $orderConverterServiceName = 'htc_now_taxi.order_converter.default';

        if (isset($config['order_converter']) && !empty($config['order_converter'])) {
            $converterSettings = $config['order_converter'];

            if (isset($converterSettings['service']) && !empty($converterSettings['service'])){
                $orderConverterServiceName = $converterSettings['service'];
            }

            if (isset($converterSettings['throw_exceptions'])){
                $container->setParameter('htc_now_taxi.order_management.throw_exceptions', (bool)$converterSettings['throw_exceptions']);
            }
        }

        $container->setAlias('htc_now_taxi.order_converter', $orderConverterServiceName);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function loadApiParameters(array $config, ContainerBuilder $container)
    {
        if (isset($config['api']) && !empty($config['api'])) {
            $api = $config['api'];

            if (isset($api['key']) && !empty($api['key'])) {
                $container->setParameter('htc_now_taxi.api.key', $api['key']);
            }

            if (isset($api['host']) && !empty($api['host'])) {
                $container->setParameter('htc_now_taxi.api.host', $api['host']);
            }
        }
    }


}
