<?php

namespace ContainerOPsuqgI;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getUserProfileRepositoryService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Repository\UserProfileRepository' shared autowired service.
     *
     * @return \App\Repository\UserProfileRepository
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['App\\Repository\\UserProfileRepository'] = new \App\Repository\UserProfileRepository(($container->services['doctrine'] ?? $container->getDoctrineService()));
    }
}
