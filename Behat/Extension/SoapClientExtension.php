<?php

namespace LaFourchette\AdobeCampaignClientBundle\Behat\Extension;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

use Behat\Behat\Extension\ExtensionInterface;

class SoapClientExtension implements ExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    Extension configuration hash (from behat.yml)
     * @param ContainerBuilder $container ContainerBuilder instance
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));
        $loader->load('services.xml');
        $loader->load('initializer.xml');

        $this->loadSoapClient($config, $container);
    }

    /**
     * Setups configuration for current extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('wsdls')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('login')->end()
                            ->scalarNode('password')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Returns compiler passes used by mink extension.
     *
     * @return array
     */
    public function getCompilerPasses()
    {
        return array();
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function loadSoapClient(array $config, ContainerBuilder $container)
    {
        $soapClientCollection = array();

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
            $container->setDefinition('la_fourchette_soap.client.' . $clientName, $definitionClient);
        }
    }
}
