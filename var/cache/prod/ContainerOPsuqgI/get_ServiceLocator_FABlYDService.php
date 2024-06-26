<?php

namespace ContainerOPsuqgI;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_FABlYDService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.f_aBlYD' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.f_aBlYD'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'doctrine' => ['services', 'doctrine', 'getDoctrineService', false],
            'repo' => ['privates', 'App\\Repository\\UserRepository', 'getUserRepositoryService', true],
            'serializer' => ['privates', 'serializer', 'getSerializerService', true],
            'validator' => ['privates', 'validator', 'getValidatorService', true],
        ], [
            'doctrine' => '?',
            'repo' => 'App\\Repository\\UserRepository',
            'serializer' => '?',
            'validator' => '?',
        ]);
    }
}
