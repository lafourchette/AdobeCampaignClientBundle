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
        $container->setParameter('adobe_campaign_client.configuration', $config);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Load client services from configuration
        foreach ($config['schemas'] as $node) {
            $this->loadClient($container, $node);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $node
     */
    protected function loadClient(ContainerBuilder $container, $node)
    {
        $definitionClient = new Definition('LaFourchette\AdobeCampaignClientBundle\Client\Client', array(
            $node['schema']
        ));
        $definitionClient->setFactoryService('la_fourchette_adobe_client.creator.client');
        $definitionClient->setFactoryMethod('create');

        $container->setDefinition('la_fourchette_adobe_client.client.' . $node['name'], $definitionClient);
    }
}
