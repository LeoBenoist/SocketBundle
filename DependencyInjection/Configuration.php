<?php

namespace Leobenoist\SocketBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('leobenoist_socket');

        $rootNode
            ->children()
            ->arrayNode('client')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('hostname')->defaultValue('localhost')->cannotBeEmpty()->end()
            ->scalarNode('port')->defaultValue('1337')->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->arrayNode('server')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('hostname')->defaultValue('localhost')->cannotBeEmpty()->end()
            ->scalarNode('port')->defaultValue('1337')->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
