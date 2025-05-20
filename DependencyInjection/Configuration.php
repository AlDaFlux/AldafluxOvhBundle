<?php

namespace Aldaflux\AldafluxOvhBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder('aldaflux_ovh');
        

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('credentials')
                    ->children()
                        ->scalarNode('application_key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('application_secret')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('endpoint_name')->defaultValue('ovh-eu')->cannotBeEmpty()->end()
                        ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('default')
                    ->children()
                        ->scalarNode('ip')->end()
                        ->scalarNode('domain')->end()
                    ->end()
                    ->end()
        ;
        
        
        

        return $treeBuilder;
    }


}
