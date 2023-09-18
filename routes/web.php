<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->post('/products/search', 'ProductController@searchProducts');
$router->post('/products/actions/add', 'ProductController@addProduct');

$router->get('/cart/actions/get', 'UserCartController@getMyCart');
$router->post('/cart/actions/add', 'UserCartController@addToCart');
$router->post('/cart/actions/delete', 'UserCartController@removeFromCart');
