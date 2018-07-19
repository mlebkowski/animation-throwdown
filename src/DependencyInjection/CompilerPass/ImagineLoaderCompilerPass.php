<?php

namespace Nassau\CartoonBattle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImagineLoaderCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $serviceId = 'ars_thanea.remote_media.imagine.data_loader.chained_data_loader';

        if ($container->has($serviceId)) {
            $definition = $container->getDefinition($serviceId);

            $definition->setMethodCalls(array_merge([
                ['addLoader', [new Reference('nassau.cartoon_battle.services.imagine.embed_card_loader')]]
            ], $definition->getMethodCalls()));
        }
    }
}
