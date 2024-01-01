<?php

namespace ContainerOPsuqgI;


use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSecurity_Authenticator_JsonLogin_MainService extends App_KernelProdContainer
{
    /*
     * Gets the private 'security.authenticator.json_login.main' shared service.
     *
     * @return \Symfony\Component\Security\Http\Authenticator\JsonLoginAuthenticator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['security.authenticator.json_login.main'] = new \Symfony\Component\Security\Http\Authenticator\JsonLoginAuthenticator(($container->privates['security.http_utils'] ?? $container->load('getSecurity_HttpUtilsService')), ($container->privates['security.user.provider.concrete.app_user_provider'] ?? $container->load('getSecurity_User_Provider_Concrete_AppUserProviderService')), NULL, NULL, ['login_path' => 'app_auth_login', 'check_path' => 'app_auth_login', 'use_forward' => false, 'require_previous_session' => false, 'username_path' => 'username', 'password_path' => 'password'], ($container->privates['property_accessor'] ?? $container->load('getPropertyAccessorService')));
    }
}
