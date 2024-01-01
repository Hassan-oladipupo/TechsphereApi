<?php

namespace ContainerAtg6xlf;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_WL4y3YDService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.WL4y3YD' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.WL4y3YD'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'repo' => ['privates', 'App\\Repository\\BlogPostRepository', 'getBlogPostRepositoryService', true],
            'serializer' => ['privates', 'serializer', 'getSerializerService', true],
            'user' => ['privates', '.errored..service_locator.WL4y3YD.App\\Entity\\User', NULL, 'Cannot autowire service ".service_locator.WL4y3YD": it needs an instance of "App\\Entity\\User" but this type has been excluded in "config/services.yaml".'],
        ], [
            'repo' => 'App\\Repository\\BlogPostRepository',
            'serializer' => '?',
            'user' => 'App\\Entity\\User',
        ]);
    }
}
