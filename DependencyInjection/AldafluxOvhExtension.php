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
    public function load(array $configs, ContainerBuilder $container) : void
    {
        /*TEST*/
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        if (isset($config['credentials']))
        {
            $container->setParameter( 'ovh_credentials', $config[ 'credentials'] );
        }
        
        if (isset($config['default']))
        {
            $container->setParameter( 'ovh_default', $config['default'] );
        }
        

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
                
        
        
    }
}
