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

$router->any('/staff/login',      'StaffAuthController@login');
$router->any('/staff/dashboard',  'StaffController@dashboard');
$router->any('/staff/orders',     'StaffController@orders');
$router->post('/staff/orders/{id:\d+}/assign', 'StaffController@assignRider');
$router->any('/staff/logout',     'StaffController@logout');
$router->any('/admin/login',      'AdminAuthController@login');
$router->any('/rider/login',      'RiderAuthController@login');

$router->any('/admin/dashboard',  'AdminController@dashboard');
$router->any('/admin/staff',      'AdminController@staff');
$router->post('/admin/staff/{id:\d+}/update', 'AdminController@updateStaff');
$router->post('/admin/staff/{id:\d+}/delete', 'AdminController@deleteStaff');
$router->any('/admin/riders',     'AdminController@riders');
$router->any('/admin/products',   'AdminController@products');
$router->any('/admin/logout',     'AdminController@logout');

return $router;
