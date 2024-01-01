<?php

namespace ContainerAtg6xlf;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_G3Mz0uWService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.G3Mz0uW' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.G3Mz0uW'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'repo' => ['privates', 'App\\Repository\\UserRepository', 'getUserRepositoryService', true],
            'user' => ['privates', '.errored..service_locator.G3Mz0uW.App\\Entity\\User', NULL, 'Cannot autowire service ".service_locator.G3Mz0uW": it needs an instance of "App\\Entity\\User" but this type has been excluded in "config/services.yaml".'],
        ], [
            'repo' => 'App\\Repository\\UserRepository',
            'user' => 'App\\Entity\\User',
        ]);
    }
}
