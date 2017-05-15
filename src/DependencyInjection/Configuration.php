<?php

namespace Nassau\CartoonBattle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cartoon_battle');

        $rootNode->children()->scalarNode('url')->isRequired();
        $rootNode->children()->scalarNode('user_id')->isRequired();
        $rootNode->children()->scalarNode('password')->isRequired();

        $cdn = $rootNode->children()->arrayNode('cdn');
        $cdn->children()->scalarNode('distribution_id')->isRequired();

        return $treeBuilder;
    }
}
