<?php

namespace TehranCode\YahooApiBundle\DependencyInjection;

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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tehran_code_yahoo_api');

        $rootNode
          ->children()
            ->scalarNode('application_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('callback_url')->isRequired()->cannotBeEmpty()->end()
			
          //end rootnode children
          ->end();
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
