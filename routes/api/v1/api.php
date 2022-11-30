<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['namespace' => 'api\v1', 'prefix' => 'v1', 'middleware' => ['api_lang']], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
        Route::post('register', 'PassportAuthController@register');
        Route::post('login', 'PassportAuthController@login');

        Route::post('check-phone', 'PhoneVerificationController@check_phone');
        Route::post('verify-phone', 'PhoneVerificationController@verify_phone');

        Route::post('check-email', 'EmailVerificationController@check_email');
        Route::post('verify-email', 'EmailVerificationController@verify_email');

        Route::post('forgot-password', 'ForgotPassword@reset_password_request');
        Route::post('verify-otp', 'ForgotPassword@otp_verification_submit');
        Route::put('reset-password', 'ForgotPassword@reset_password_submit');

        Route::any('social-login', 'SocialAuthController@social_login');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });

    Route::group(['prefix' => 'shipping-method', 'middleware' => 'auth:api'], function () {
        Route::get('detail/{id}', 'ShippingMethodController@get_shipping_method_info');
        Route::get('by-seller/{id}/{seller_is}', 'ShippingMethodController@shipping_methods_by_seller');
        Route::post('choose-for-order', 'ShippingMethodController@choose_for_order');
        Route::get('chosen', 'ShippingMethodController@chosen_shipping_methods');

        Route::get('ongkir/{user_id}/{product_id}', 'ShippingMethodController@get_rajaongkir');
    });

    Route::group(['prefix' => 'cart', 'middleware' => 'auth:api'], function () {
        Route::get('/', 'CartController@cart');
        Route::post('add', 'CartController@add_to_cart');
        Route::put('update', 'CartController@update_cart');
        Route::delete('remove', 'CartController@remove_from_cart');
    });

    Route::get('country', 'AttributeController@country');
    Route::get('short-country', 'AttributeController@short_country');

    Route::get('faq', 'GeneralController@faq');

    Route::group(['prefix' => 'products'], function () {
        Route::get('short_latest/{country}', 'ProductController@short_latest_products');
        Route::get('short_featured/{country}', 'ProductController@short_featured_products');
        Route::get('short_top-rated/{country}', 'ProductController@short_top_rated_products');
        Route::get('short_best-sellings/{country}', 'ProductController@short_best_sellings');
        Route::get('short_home-categories/{country}', 'ProductController@short_home_categories');
        Route::get('short_flash-deal/{deal_id}/{country}', 'ProductController@short_flash_deal');

        Route::get('latest', 'ProductController@get_latest_products');
        Route::get('featured', 'ProductController@get_featured_products');
        Route::get('top-rated', 'ProductController@get_top_rated_products');
        Route::get('search', 'ProductController@get_searched_products');
        Route::get('details/{id}', 'ProductController@get_product');
        Route::get('related-products/{product_id}', 'ProductController@get_related_products');
        Route::get('reviews/{product_id}', 'ProductController@get_product_reviews');
        Route::get('rating/{product_id}', 'ProductController@get_product_rating');
        Route::get('counter/{product_id}', 'ProductController@counter');
        Route::get('shipping-methods', 'ProductController@get_shipping_methods');
        Route::get('social-share-link/{product_id}', 'ProductController@social_share_link');
        Route::post('reviews/submit', 'ProductController@submit_product_review')->middleware('auth:api');
        Route::get('best-sellings', 'ProductController@get_best_sellings');
        Route::get('home-categories', 'ProductController@get_home_categories');
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@get_notifications');
    });

    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', 'BrandController@get_brands');
        Route::get('products/{brand_id}', 'BrandController@get_products');
    });

    Route::group(['prefix' => 'attributes'], function () {
        Route::get('/', 'AttributeController@get_attributes');
    });

    Route::group(['prefix' => 'flash-deals'], function () {
        Route::get('/', 'FlashDealController@get_flash_deal');
        Route::get('products/{deal_id}', 'FlashDealController@get_products');
    });

    Route::group(['prefix' => 'deals'], function () {
        Route::get('featured', 'DealController@get_featured_deal');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@get_categories');
        Route::get('products/{category_id}', 'CategoryController@get_products');
        Route::get('products/{category_id}/{country}', 'CategoryController@short_get_products');
    });

    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
        Route::get('info', 'CustomerController@info');
        Route::put('update-profile', 'CustomerController@update_profile');
        Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');

        Route::group(['prefix' => 'address'], function () {
            Route::get('list', 'CustomerController@address_list');
            Route::post('add', 'CustomerController@add_new_address');
            Route::delete('/', 'CustomerController@delete_address');
        });

        Route::group(['prefix' => 'support-ticket'], function () {
            Route::post('create', 'CustomerController@create_support_ticket');
            Route::get('get', 'CustomerController@get_support_tickets');
            Route::get('conv/{ticket_id}', 'CustomerController@get_support_ticket_conv');
            Route::post('reply/{ticket_id}', 'CustomerController@reply_support_ticket');
        });

        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('/', 'CustomerController@wish_list');
            Route::post('add', 'CustomerController@add_to_wishlist');
            Route::delete('remove', 'CustomerController@remove_from_wishlist');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('list', 'CustomerController@get_order_list');
            Route::get('details', 'CustomerController@get_order_details');
            Route::get('place', 'OrderController@place_order');
        });
        // Chatting
        Route::group(['prefix' => 'chat'], function () {
            Route::get('/', 'ChatController@chat_with_seller');
            Route::get('messages', 'ChatController@messages');
            Route::post('send-message', 'ChatController@messages_store');
        });
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('track', 'OrderController@track_order');
    });

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'seller'], function () {
        Route::get('/', 'SellerController@get_seller_info');
        Route::get('{seller_id}/products', 'SellerController@get_seller_products');
        Route::get('top', 'SellerController@get_top_sellers');
        Route::get('all', 'SellerController@get_all_sellers');
    });

    Route::group(['prefix' => 'coupon', 'middleware' => 'auth:api'], function () {
        Route::get('apply', 'CouponController@apply');
    });
});
