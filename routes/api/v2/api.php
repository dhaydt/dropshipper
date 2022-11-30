<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'api\v2', 'prefix' => 'v2','middleware' => ['api_lang']], function () {
    Route::group(['prefix' => 'seller', 'namespace' => 'seller'], function () {

        Route::get('seller-info', 'SellerController@seller_info');
        Route::get('shop-product-reviews', 'SellerController@shop_product_reviews');
        Route::put('seller-update', 'SellerController@seller_info_update');
        Route::get('monthly-earning', 'SellerController@monthly_earning');
        Route::get('monthly-commission-given', 'SellerController@monthly_commission_given');

        Route::get('shop-info', 'SellerController@shop_info');
        Route::get('transactions', 'SellerController@transaction');
        Route::put('shop-update', 'SellerController@shop_info_update');

        Route::post('balance-withdraw', 'SellerController@withdraw_request');
        Route::delete('close-withdraw-request', 'SellerController@close_withdraw_request');

        Route::group(['prefix' => 'products'], function () {
            Route::post('upload-images', 'ProductController@upload_images');
            Route::post('add', 'ProductController@add_new');
            Route::get('list', 'ProductController@list');
            Route::get('edit/{id}', 'ProductController@edit');
            Route::put('update/{id}', 'ProductController@update');
            Route::delete('delete/{id}', 'ProductController@delete');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('list', 'OrderController@list');
            Route::get('/{id}', 'OrderController@details');
            Route::put('order-detail-status/{id}','OrderController@order_detail_status');
        });

        Route::group(['prefix' => 'shipping-method'], function () {
            Route::get('list', 'ShippingMethodController@list');
            Route::post('add', 'ShippingMethodController@store');
            Route::get('edit/{id}', 'ShippingMethodController@edit');
            Route::put('status', 'ShippingMethodController@status_update');
            Route::put('update/{id}', 'ShippingMethodController@update');
            Route::delete('delete/{id}','ShippingMethodController@delete');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::get('list', 'ChatController@messages');
            Route::post('send', 'ChatController@send_message');
        });

        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');
        });
    });
    Route::post('ls-lib-update', 'LsLibController@lib_update');
});
