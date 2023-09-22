<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->get('/cart/actions/get', 'UserCartController@getMyCart');
$router->post('/cart/actions/add', 'UserCartController@addToCart');
$router->post('/cart/actions/delete', 'UserCartController@removeFromCart');

$router->group(['prefix' => 'categories'], function () use ($router) {
    $router->post('actions/upsert', [ 'middleware' => 'auth.check:product,category_add', 'uses' => 'ProductCategoryController@upsert_category']);
    $router->get('actions/list', [ 'middleware' => 'auth.check:product,category_list', 'uses' => 'ProductCategoryController@list']);
    $router->post('actions/delete', [ 'middleware' => 'auth.check:product,category_del', 'uses' => 'ProductCategoryController@del_category']);
});

$router->group(['prefix' => 'products'], function () use ($router) {
    $router->post('search', [ 'middleware' => 'auth.check:product,serach', 'uses' => 'ProductController@searchProducts']);
    $router->post('actions/add', [ 'middleware' => 'auth.check:product,add', 'uses' => 'ProductController@addProduct']);
    $router->post('actions/edit', [ 'middleware' => 'auth.check:product,edit', 'uses' => 'ProductController@editProduct']);
});
