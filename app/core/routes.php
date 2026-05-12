<?php
$router = new Router();

$router->any('/',                 'HomeController@index');
$router->any('/home',             'HomeController@index');

$router->any('/menu',             'MenuController@index');
$router->any('/promos',           'PromosController@index');

$router->any('/product/{id:\d+}', 'ProductController@show');

$router->any('/cart',             'CartController@index');
$router->post('/add_to_cart',     'CartController@add');

$router->any('/checkout',         'CheckoutController@index');
$router->post('/place_order',     'CheckoutController@place');

$router->any('/order_tracking',   'OrderController@tracking');
$router->any('/account',          'AccountController@index');

$router->any('/login',            'AuthController@login');
$router->any('/register',         'AuthController@register');
$router->any('/forgot_password',  'AuthController@forgotPassword');
$router->any('/logout',           'AuthController@logout');

return $router;
