<?php

namespace App;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        $typeMapper = $container->getDefinition('dunglas_doctrine_json_odm.type_mapper');
        $types = $typeMapper->getArgument(0);
        $services = $container->findTaggedServiceIds('game.component');
        $components = [];
        foreach (array_keys($services) as $componentClass) {
            /** @var class-string<GameComponentInterface> $componentClass */
            $components[$componentClass::getId()] = $componentClass;
        }
        $typeMapper->setArgument(0, array_merge($types, $components));
    }
}
