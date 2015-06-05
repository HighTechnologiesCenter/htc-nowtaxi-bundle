<?php

namespace Htc\NowTaxiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('htc_now_taxi');
        $rootNode
            ->children()
                ->append($this->createOrderConverterNode())
                ->append($this->createApiNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function createApiNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('api');
        $node
            ->children()
                ->scalarNode('host')->cannotBeEmpty()->end()
                ->scalarNode('key')->cannotBeEmpty()->end()
            ->end();

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function createOrderConverterNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('order_converter');
        $node
            ->children()
                ->scalarNode('service')->cannotBeEmpty()->end()
                ->booleanNode('throw_exceptions')->defaultTrue()->end()
            ->end();

        return $node;
    }
}
