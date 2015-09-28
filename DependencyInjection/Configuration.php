<?php

namespace LaFourchette\AdobeCampaignClientBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('adobe_campaign_client');

        $rootNode
            ->children()
                ->scalarNode('path')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('login')->end()
                ->scalarNode('password')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
