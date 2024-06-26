<?php

namespace ContainerOPsuqgI;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getProfileSettingControllerService extends App_KernelProdContainer
{
    /*
     * Gets the public 'App\Controller\ProfileSettingController' shared autowired service.
     *
     * @return \App\Controller\ProfileSettingController
     */
    public static function do($container, $lazyLoad = true)
    {
        $container->services['App\\Controller\\ProfileSettingController'] = $instance = new \App\Controller\ProfileSettingController(($container->privates['logger'] ?? $container->getLoggerService()));

        $instance->setContainer(($container->privates['.service_locator.CshazM0'] ?? $container->load('get_ServiceLocator_CshazM0Service'))->withContext('App\\Controller\\ProfileSettingController', $container));

        return $instance;
    }
}
