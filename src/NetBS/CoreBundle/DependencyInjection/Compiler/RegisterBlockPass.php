<?php

namespace NetBS\CoreBundle\DependencyInjection\Compiler;

use NetBS\CoreBundle\Utils\DIHelper;
use NetBS\CoreBundle\Utils\Traits\TwigTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RegisterBlockPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $manager    = $container->findDefinition('netbs.core.blocks_manager');

        foreach($container->findTaggedServiceIds('netbs.block') as $id => $params) {

            $definition = $container->findDefinition($id);
            $reflexion  = new \ReflectionClass($definition->getClass());

            if(in_array(TwigTrait::class, DIHelper::getTraits($reflexion)))
                $definition->addMethodCall('setTwig', [new Reference('twig')]);

            $manager->addMethodCall('registerBlock', [new Reference($id)]);
        }
    }
}
