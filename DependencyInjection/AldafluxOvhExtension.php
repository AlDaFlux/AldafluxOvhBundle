<?php

namespace Aldaflux\AldafluxOvhBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class AldafluxOvhExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        /*
        $container->setParameter( 'aldaflux_ovh.parameter1', $config[ 'parameter1' ] );
        */

                        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
                $loader->load('services.yml');
                
        
        
    }
}
