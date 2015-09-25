<?php

namespace LaFourchette\AdobeCampaignClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AdobeCampaignClientExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->loadSoapClient($config, $container);

    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function loadSoapClient(array $config, ContainerBuilder $container)
    {
        foreach ($config['wsdls'] as $clientName => $node) {
            // Define token by configuration
            $definitionToken = new Definition('LaFourchette\AdobeCampaignClientBundle\SoapClient\Token', array(
                $node['login'],
                $node['password'],
            ));
            // Define Client by configuration
            $definitionClient = new Definition('LaFourchette\AdobeCampaignClientBundle\SoapClient\Client', array(
                $node['path'],
            ));
            // Set Token for client api
            $definitionClient->addMethodCall('setToken', array($definitionToken));
            $definitionClient->setPublic(false);
            $container->setDefinition('la_fourchette_soap.client.' . $clientName, $definitionClient);
        }
    }
}
