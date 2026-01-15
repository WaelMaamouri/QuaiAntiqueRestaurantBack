<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/doc' => [[['_route' => 'nelmio_api_doc', '_controller' => 'nelmio_api_doc.controller.swagger_ui'], null, null, null, false, false, null]],
        '/api/booking' => [[['_route' => 'app_api_booking_app_booking_new', '_controller' => 'App\\Controller\\BookingController::new'], null, ['POST' => 0], null, false, false, null]],
        '/api/category' => [[['_route' => 'app_api_category_app_category_new', '_controller' => 'App\\Controller\\CategoryController::new'], null, ['POST' => 0], null, false, false, null]],
        '/' => [[['_route' => 'app_default_home', '_controller' => 'App\\Controller\\DefaultController::home'], null, null, null, false, false, null]],
        '/api/food' => [[['_route' => 'app_api_food_app_food_new', '_controller' => 'App\\Controller\\FoodController::new'], null, ['POST' => 0], null, false, false, null]],
        '/api/menu' => [[['_route' => 'api_app_menuapp_menu_new', '_controller' => 'App\\Controller\\MenuController::new'], null, ['POST' => 0], null, false, false, null]],
        '/api/picture' => [[['_route' => 'app_api_picture_app_picture_new', '_controller' => 'App\\Controller\\PictureController::new'], null, ['POST' => 0], null, false, false, null]],
        '/api/restaurant' => [[['_route' => 'app_api_restaurant_app_restaurant_new', '_controller' => 'App\\Controller\\RestaurantController::new'], null, ['POST' => 0], null, false, false, null]],
        '/api/registration' => [[['_route' => 'app_api_account_registration', '_controller' => 'App\\Controller\\SecurityController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'app_api_account_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/account' => [[['_route' => 'app_api_account_account', '_controller' => 'App\\Controller\\SecurityController::account'], null, ['GET' => 0], null, false, false, null]],
        '/api/account/edit' => [[['_route' => 'app_api_account_edit', '_controller' => 'App\\Controller\\SecurityController::edit'], null, ['PUT' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|booking/([^/]++)(?'
                        .'|(*:69)'
                    .')'
                    .'|category/([^/]++)(?'
                        .'|(*:97)'
                    .')'
                    .'|food/([^/]++)(?'
                        .'|(*:121)'
                    .')'
                    .'|menu/([^/]++)(?'
                        .'|(*:146)'
                    .')'
                    .'|picture/([^/]++)(?'
                        .'|(*:174)'
                    .')'
                    .'|restaurant/([^/]++)(?'
                        .'|(*:205)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        69 => [
            [['_route' => 'app_api_booking_show', '_controller' => 'App\\Controller\\BookingController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_booking_edit', '_controller' => 'App\\Controller\\BookingController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_booking_delete', '_controller' => 'App\\Controller\\BookingController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        97 => [
            [['_route' => 'app_api_category_show', '_controller' => 'App\\Controller\\CategoryController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_category_edit', '_controller' => 'App\\Controller\\CategoryController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_category_delete', '_controller' => 'App\\Controller\\CategoryController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        121 => [
            [['_route' => 'app_api_food_show', '_controller' => 'App\\Controller\\FoodController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_food_edit', '_controller' => 'App\\Controller\\FoodController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_food_delete', '_controller' => 'App\\Controller\\FoodController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        146 => [
            [['_route' => 'api_app_menushow', '_controller' => 'App\\Controller\\MenuController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_app_menuedit', '_controller' => 'App\\Controller\\MenuController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'api_app_menudelete', '_controller' => 'App\\Controller\\MenuController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        174 => [
            [['_route' => 'app_api_picture_show', '_controller' => 'App\\Controller\\PictureController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_picture_edit', '_controller' => 'App\\Controller\\PictureController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_picture_delete', '_controller' => 'App\\Controller\\PictureController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        205 => [
            [['_route' => 'app_api_restaurant_show', '_controller' => 'App\\Controller\\RestaurantController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_restaurant_edit', '_controller' => 'App\\Controller\\RestaurantController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_restaurant_delete', '_controller' => 'App\\Controller\\RestaurantController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
