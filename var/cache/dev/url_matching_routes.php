<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/blog-post/add' => [[['_route' => 'app_blog_posts_add', '_controller' => 'App\\Controller\\BlogPostController::addBlog'], null, ['POST' => 0], null, false, false, null]],
        '/blog/post' => [[['_route' => 'app_blog_post', '_controller' => 'App\\Controller\\BlogPostController::index'], null, ['GET' => 0], null, false, false, null]],
        '/blog-post/top-liked' => [[['_route' => 'app_blog_topliked', '_controller' => 'App\\Controller\\BlogPostController::topLiked'], null, ['GET' => 0], null, false, false, null]],
        '/blog-post/follows' => [[['_route' => 'app_blog_post_follows', '_controller' => 'App\\Controller\\BlogPostController::followPosts'], null, ['GET' => 0], null, false, false, null]],
        '/login' => [[['_route' => 'app_auth_login', '_controller' => 'App\\Controller\\LoginController::login'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/logout' => [[['_route' => 'app_auth_logout', '_controller' => 'App\\Controller\\LoginController::logout'], null, ['POST' => 0], null, false, false, null]],
        '/settings/profile' => [[['_route' => 'app_settings_profile', '_controller' => 'App\\Controller\\ProfileSettingController::profile'], null, ['POST' => 0], null, false, false, null]],
        '/settings/profile-image' => [[['_route' => 'app_settings_profile_image', '_controller' => 'App\\Controller\\ProfileSettingController::profileImage'], null, ['POST' => 0], null, false, false, null]],
        '/api/register' => [[['_route' => 'api_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api/confirm-email' => [[['_route' => 'api_confirm_email', '_controller' => 'App\\Controller\\RegistrationController::confirmEmail'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/blog\\-post/([^/]++)(?'
                    .'|(*:65)'
                    .'|/(?'
                        .'|edit(*:80)'
                        .'|comment(*:94)'
                    .')'
                .')'
                .'|/Follow/([^/]++)(*:119)'
                .'|/un(?'
                    .'|Follow/([^/]++)(*:148)'
                    .'|like/([^/]++)(*:169)'
                .')'
                .'|/like/([^/]++)(*:192)'
                .'|/profile/([^/]++)(?'
                    .'|(*:220)'
                    .'|/follow(?'
                        .'|ing(*:241)'
                        .'|ers(*:252)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        65 => [[['_route' => 'app_blog_post_show', '_controller' => 'App\\Controller\\BlogPostController::showOne'], ['blog'], ['GET' => 0], null, false, true, null]],
        80 => [[['_route' => 'app_blog_post_edit', '_controller' => 'App\\Controller\\BlogPostController::editBlog'], ['blog'], ['PUT' => 0], null, false, false, null]],
        94 => [[['_route' => 'app_blog_post_comment', '_controller' => 'App\\Controller\\BlogPostController::addComment'], ['blog'], ['POST' => 0], null, false, false, null]],
        119 => [[['_route' => 'app_Follow', '_controller' => 'App\\Controller\\FollowersController::follow'], ['id'], ['POST' => 0], null, false, true, null]],
        148 => [[['_route' => 'app_unFollow', '_controller' => 'App\\Controller\\FollowersController::unFollow'], ['id'], ['POST' => 0], null, false, true, null]],
        169 => [[['_route' => 'app_unlike', '_controller' => 'App\\Controller\\LikeController::unlike'], ['id'], ['POST' => 0], null, false, true, null]],
        192 => [[['_route' => 'app_like', '_controller' => 'App\\Controller\\LikeController::like'], ['id'], ['POST' => 0], null, false, true, null]],
        220 => [[['_route' => 'app_profile', '_controller' => 'App\\Controller\\ProfileController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        241 => [[['_route' => 'app_profile_follwingr', '_controller' => 'App\\Controller\\ProfileController::following'], ['id'], ['GET' => 0], null, false, false, null]],
        252 => [
            [['_route' => 'app_profile_followers', '_controller' => 'App\\Controller\\ProfileController::followers'], ['id'], ['GET' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
