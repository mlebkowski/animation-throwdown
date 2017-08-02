<?php

namespace Nassau\CartoonBattle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CartoonBattleExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs = (new Processor)->processConfiguration(new Configuration, $configs);

        $container->setParameter('cartoon_battle.url', $configs['url']);
        $container->setParameter('cartoon_battle.user_id', $configs['user_id']);
        $container->setParameter('cartoon_battle.password', $configs['password']);

        $container->setParameter('cartoon_battle.cdn.distribution_id', $configs['cdn']['distribution_id']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('forms.xml');
        $loader->load('controllers.xml');

    }

}
