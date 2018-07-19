<?php

namespace Nassau\CartoonBattle;

use Nassau\CartoonBattle\DependencyInjection\CompilerPass\ImagineLoaderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CartoonBattleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ImagineLoaderCompilerPass());
    }

}
