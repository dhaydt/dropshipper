<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'api\v2', 'prefix' => 'v2', 'middleware' => ['api_lang']], function () {
    Route::get('termsandcondition', 'LsLibController@term_and_condition');
    Route::group(['prefix' => 'seller', 'namespace' => 'seller'], function () {
        Route::get('seller-info', 'SellerController@seller_info');
        Route::get('shop-product-reviews', 'SellerController@shop_product_reviews');
        Route::post('seller-update', 'SellerController@seller_info_update');
        Route::post('update-password', 'SellerController@update_password');
        Route::get('monthly-earning', 'SellerController@monthly_earning');
        Route::get('monthly-commission-given', 'SellerController@monthly_commission_given');

        Route::post('update-cm-firebase-token', 'SellerController@put_fcm');

        Route::get('shop-info', 'SellerController@shop_info');
        Route::get('transactions', 'SellerController@transaction');
        Route::put('shop-update', 'SellerController@shop_info_update');

        Route::post('balance-withdraw', 'SellerController@withdraw_request');
        Route::delete('close-withdraw-request', 'SellerController@close_withdraw_request');

        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('/', 'SellerController@wish_list');
            Route::post('add', 'SellerController@add_to_wishlist');
            Route::delete('remove', 'SellerController@remove_from_wishlist');
        });

        Route::group(['prefix' => 'products'], function () {
            Route::post('upload-images', 'ProductController@upload_images');
            Route::post('add', 'ProductController@add_new');
            Route::get('list', 'ProductController@list');
            Route::get('edit/{id}', 'ProductController@edit');
            Route::put('update/{id}', 'ProductController@update');
            Route::delete('delete/{id}', 'ProductController@delete');
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', 'CartController@cart');
            Route::post('add', 'CartController@add_to_cart');
            Route::post('update', 'CartController@update_cart');
            Route::delete('remove', 'CartController@remove_from_cart');
        });

        Route::group(['prefix' => 'address'], function () {
            Route::post('customer-address', 'AddressController@add_new_address');

            // Select province city district
            Route::get('province', 'AddressController@getProvince');
            Route::get('city', 'AddressController@getCity');
            Route::get('district', 'AddressController@getDistrict');
        });

        Route::group(['prefix' => 'shipping-method'], function () {
            Route::get('detail/{id}', 'ShippingMethodController@get_shipping_method_info');
            Route::get('by-seller/{id}/{seller_is}', 'ShippingMethodController@shipping_methods_by_seller');
            Route::post('choose-for-order', 'ShippingMethodController@choose_for_order');
            Route::get('chosen', 'ShippingMethodController@chosen_shipping_methods');
            Route::post('set-kurir', 'ResiController@set_kurir');
            Route::delete('delete-kurir/', 'ResiController@delete_kurir');
            Route::get('get-kurir/', 'ResiController@get_kurir');

            Route::get('ongkir', 'ShippingMethodController@get_rajaongkir');
        });

        Route::group(['prefix' => 'payment'], function () {
            Route::get('/', 'PaymentController@index');
            Route::post('create-invoice', 'PaymentController@invoice');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('list', 'OrderController@list');
            Route::post('cancel', 'OrderController@cancel');
            Route::get('/{id}', 'OrderController@details');
            Route::put('order-detail-status/{id}', 'OrderController@order_detail_status');
        });

        Route::get('place-order', 'OrderController@putOrder');

        Route::group(['prefix' => 'shipping-method'], function () {
            Route::get('list', 'ShippingMethodController@list');
            Route::post('add', 'ShippingMethodController@store');
            Route::get('edit/{id}', 'ShippingMethodController@edit');
            Route::put('status', 'ShippingMethodController@status_update');
            Route::put('update/{id}', 'ShippingMethodController@update');
            Route::delete('delete/{id}', 'ShippingMethodController@delete');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::get('list', 'ChatController@messages');
            Route::post('send', 'ChatController@send_message');
        });

        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');
            Route::post('register', 'LoginController@register');
            Route::post('check-phone', 'PhoneVerificationController@check_phone');
            Route::post('verify-phone', 'PhoneVerificationController@verify_phone');

            Route::post('forgot-password', 'ForgotPassword@reset_password_request');
            Route::post('verify-otp', 'ForgotPassword@otp_verification_submit');
            Route::put('reset-password', 'ForgotPassword@reset_password_submit');
        });

        Route::group(['prefix' => 'coupon'], function () {
            Route::get('apply', 'CouponController@apply');
        });
    });
    Route::post('ls-lib-update', 'LsLibController@lib_update');
});
