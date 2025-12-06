<?php

namespace App;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Mastery\Mastery;
use App\GameElement\Mastery\MasterySet;
use App\GameElement\Mastery\MasteryType;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        self::addJsonDocumentMappings($container);
        self::subscribeComponents($container);
    }

    private function addJsonDocumentMappings(ContainerBuilder $container): void
    {
        $typeMapper = $container->getDefinition('dunglas_doctrine_json_odm.type_mapper');
        $types = $typeMapper->getArgument(0);

        // Subscribe components
        $services = $container->findTaggedServiceIds('game.component');
        $components = [];
        foreach (array_keys($services) as $componentClass) {
            /** @var class-string<GameComponentInterface> $componentClass */
            $components[$componentClass::getId()] = $componentClass;
        }

        // Subscribe mastery types
        $services = $container->findTaggedServiceIds('mastery.type');
        $masteryTypes = [];
        foreach (array_keys($services) as $typeClass) {
            /** @var class-string<MasteryType> $typeClass */
            $masteryTypes[$typeClass::getId()] = $typeClass;
        }
        $masteryTypes['mastery'] = Mastery::class;
        $masteryTypes['mastery_set'] = MasterySet::class;


        $typeMapper->setArgument(0, array_merge($types, $components, $masteryTypes));
    }

    public function subscribeComponents(ContainerBuilder $container): void
    {
        $services = $container->findTaggedServiceIds('game.component');
        $components = [];
        foreach (array_keys($services) as $componentClass) {
            /** @var class-string<GameComponentInterface> $componentClass */
            $components[$componentClass::getId()] = $componentClass;
        }
        $container->setParameter('game.components', $components);
    }
}
