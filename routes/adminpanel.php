<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'prefix' => 'adminpanel', 'as' => 'admin.'], function () {
    Route::get('/', function () {
        return redirect()->route('admin.auth.login');
    });

    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });

    /*authenticated*/
    Route::group(['middleware' => ['admin']], function () {
        //dashboard routes
        Route::get('/', 'DashboardController@dashboard')->name('dashboard'); //previous dashboard route
        Route::get('/checkDropshipper', 'AttributeController@checkDropshipper')->name('checkDropshipper'); //previous dashboard route
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('/', 'DashboardController@dashboard')->name('index');
            Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
            Route::post('business-overview', 'DashboardController@business_overview')->name('business-overview');
        });
        //system routes
        Route::get('search-function', 'SystemController@search_function')->name('search-function');
        Route::get('maintenance-mode', 'SystemController@maintenance_mode')->name('maintenance-mode');

        Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.', 'middleware' => ['module:employee_section']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store');
            Route::get('update/{id}', 'CustomRoleController@edit')->name('update');
            Route::post('update/{id}', 'CustomRoleController@update');
        });

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            Route::get('update/{id}', 'ProfileController@edit')->name('update');
            Route::post('update/{id}', 'ProfileController@update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');
        });

        Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.', 'middleware' => ['module:user_section']], function () {
            Route::post('update/{id}', 'WithdrawController@update')->name('update');
            Route::post('request', 'WithdrawController@w_request')->name('request');
            Route::post('status-filter', 'WithdrawController@status_filter')->name('status-filter');
        });

        Route::group(['prefix' => 'deal', 'as' => 'deal.', 'middleware' => ['module:marketing_section']], function () {
            Route::get('flash', 'DealController@flash_index')->name('flash');
            Route::post('flash', 'DealController@flash_submit');

            // feature deal
            Route::get('feature', 'DealController@feature_index')->name('feature');

            Route::get('day', 'DealController@deal_of_day')->name('day');
            Route::post('day', 'DealController@deal_of_day_submit');
            Route::post('day-status-update', 'DealController@day_status_update')->name('day-status-update');

            Route::get('day-update/{id}', 'DealController@day_edit')->name('day-update');
            Route::post('day-update/{id}', 'DealController@day_update');
            Route::get('day-delete/{id}', 'DealController@day_delete')->name('day-delete');

            Route::get('update/{id}', 'DealController@edit')->name('update');
            Route::get('edit/{id}', 'DealController@feature_edit')->name('edit');

            Route::post('update/{id}', 'DealController@update')->name('update');
            Route::post('status-update', 'DealController@status_update')->name('status-update');
            Route::post('feature-status', 'DealController@feature_status')->name('feature-status');

            Route::post('featured-update', 'DealController@featured_update')->name('featured-update');
            Route::get('add-product/{deal_id}', 'DealController@add_product')->name('add-product');
            Route::post('add-product/{deal_id}', 'DealController@add_product_submit');
            Route::get('delete-product/{deal_product_id}', 'DealController@delete_product')->name('delete-product');

            // Produk unggulan
            Route::get('unggulan', 'DealController@unggulan_index')->name('unggulan');
            Route::post('unggulan', 'DealController@unggulan_submit');

            Route::post('status-update-unggulan', 'DealController@status_update_unggulan')->name('status-update-unggulan');
            Route::get('update-unggulan/{id}', 'DealController@edit_unggulan')->name('update-unggulan');
            Route::post('update-unggulan/{id}', 'DealController@update_unggulan')->name('update-unggulan');

            // Diskon Berlimpah
            Route::get('berlimpah', 'DealController@berlimpah_index')->name('berlimpah');

            Route::get('update-unggulan/{id}', 'DealController@edit_unggulan')->name('update-unggulan');
            Route::post('update-unggulan/{id}', 'DealController@update_unggulan')->name('update-unggulan');
            Route::get('add-category/{deal_id}', 'DealController@add_category')->name('add-category');
            Route::post('add-category/{deal_id}', 'DealController@add_category_submit');
            Route::post('banner-berlimpah', 'DealController@banner_berlimpah')->name('banner-berlimpah');
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.', 'middleware' => ['module:employee_section']], function () {
            Route::get('add-new', 'EmployeeController@add_new')->name('add-new');
            Route::post('add-new', 'EmployeeController@store');
            Route::get('list', 'EmployeeController@list')->name('list');
            Route::get('update/{id}', 'EmployeeController@edit')->name('update');
            Route::post('update/{id}', 'EmployeeController@update');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'CategoryController@index')->name('view');
            Route::get('fetch', 'CategoryController@fetch')->name('fetch');
            Route::post('store', 'CategoryController@store')->name('store');
            Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
            Route::post('update/{id}', 'CategoryController@update')->name('update');
            Route::post('delete', 'CategoryController@delete')->name('delete');
            Route::get('status/{id}/{home_status}', 'CategoryController@status')->name('status');
        });

        Route::group(['prefix' => 'sub-category', 'as' => 'sub-category.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'SubCategoryController@index')->name('view');
            Route::get('fetch', 'SubCategoryController@fetch')->name('fetch');
            Route::post('store', 'SubCategoryController@store')->name('store');
            Route::post('edit', 'SubCategoryController@edit')->name('edit');
            Route::post('update', 'SubCategoryController@update')->name('update');
            Route::post('delete', 'SubCategoryController@delete')->name('delete');
        });

        Route::group(['prefix' => 'sub-sub-category', 'as' => 'sub-sub-category.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'SubSubCategoryController@index')->name('view');
            Route::get('fetch', 'SubSubCategoryController@fetch')->name('fetch');
            Route::post('store', 'SubSubCategoryController@store')->name('store');
            Route::post('edit', 'SubSubCategoryController@edit')->name('edit');
            Route::post('update', 'SubSubCategoryController@update')->name('update');
            Route::post('delete', 'SubSubCategoryController@delete')->name('delete');
            Route::post('get-sub-category', 'SubSubCategoryController@getSubCategory')->name('getSubCategory');
            Route::post('get-category-id', 'SubSubCategoryController@getCategoryId')->name('getCategoryId');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.', 'middleware' => ['module:product_management']], function () {
            Route::get('add-new', 'BrandController@add_new')->name('add-new');
            Route::post('add-new', 'BrandController@store');
            Route::get('list', 'BrandController@list')->name('list');
            Route::get('update/{id}', 'BrandController@edit')->name('update');
            Route::post('update/{id}', 'BrandController@update');
            Route::post('delete', 'BrandController@delete')->name('delete');
        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:marketing_section']], function () {
            Route::post('add-new', 'BannerController@store')->name('store');
            Route::get('list', 'BannerController@list')->name('list');
            Route::post('delete', 'BannerController@delete')->name('delete');
            Route::post('status', 'BannerController@status')->name('status');
            Route::post('edit', 'BannerController@edit')->name('edit');
            Route::post('update', 'BannerController@update')->name('update');
        });

        Route::group(['prefix' => 'attribute', 'as' => 'attribute.', 'middleware' => ['module:product_management']], function () {
            Route::get('view', 'AttributeController@index')->name('view');
            Route::get('fetch', 'AttributeController@fetch')->name('fetch');
            Route::post('store', 'AttributeController@store')->name('store');
            Route::get('edit/{id}', 'AttributeController@edit')->name('edit');
            Route::post('update/{id}', 'AttributeController@update')->name('update');
            Route::post('delete', 'AttributeController@delete')->name('delete');
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => ['module:marketing_section']], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new');
            Route::post('add-new', 'CouponController@store');
            Route::get('update/{id}', 'CouponController@edit')->name('update');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
        });
        Route::group(['prefix' => 'social-login', 'as' => 'social-login.', 'middleware' => ['module:business_settings']], function () {
            Route::get('view', 'BusinessSettingsController@viewSocialLogin')->name('view');
            Route::post('update/{service}', 'BusinessSettingsController@updateSocialLogin')->name('update');
        });

        Route::group(['prefix' => 'currency', 'as' => 'currency.', 'middleware' => ['module:business_settings']], function () {
            Route::get('view', 'CurrencyController@index')->name('view');
            Route::get('fetch', 'CurrencyController@fetch')->name('fetch');
            Route::post('store', 'CurrencyController@store')->name('store');
            Route::get('edit/{id}', 'CurrencyController@edit')->name('edit');
            Route::post('update/{id}', 'CurrencyController@update')->name('update');
            Route::get('delete/{id}', 'CurrencyController@delete')->name('delete');
            Route::post('status', 'CurrencyController@status')->name('status');
            Route::post('system-currency-update', 'CurrencyController@systemCurrencyUpdate')->name('system-currency-update');
        });

        Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.', 'middleware' => ['module:support_section']], function () {
            Route::get('view', 'SupportTicketController@index')->name('view');
            Route::post('status', 'SupportTicketController@status')->name('status');
            Route::get('single-ticket/{id}', 'SupportTicketController@single_ticket')->name('singleTicket');
            Route::post('single-ticket/{id}', 'SupportTicketController@replay_submit')->name('replay');
        });
        Route::group(['prefix' => 'notification', 'as' => 'notification.', 'middleware' => ['module:marketing_section']], function () {
            Route::get('add-new', 'NotificationController@index')->name('add-new');
            Route::post('store', 'NotificationController@store')->name('store');
            Route::get('edit/{id}', 'NotificationController@edit')->name('edit');
            Route::post('update/{id}', 'NotificationController@update')->name('update');
            Route::post('status', 'NotificationController@status')->name('status');
            Route::post('delete', 'NotificationController@delete')->name('delete');
        });
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.', 'middleware' => ['module:business_section']], function () {
            Route::get('list', 'ReviewsController@list')->name('list');
        });

        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['module:user_section']], function () {
            Route::get('list', 'CustomerController@customer_list')->name('list');
            Route::post('status-update', 'CustomerController@status_update')->name('status-update');
            Route::get('view/{user_id}', 'CustomerController@view')->name('view');
        });
        ///Report
        Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['module:report']], function () {
            Route::get('order', 'ReportController@order_index')->name('order');
            Route::get('earning', 'ReportController@earning_index')->name('earning');
            Route::post('set-date', 'ReportController@set_date')->name('set-date');
            //sale report inhouse
            Route::get('inhoue-product-sale', 'InhouseProductSaleController@index')->name('inhoue-product-sale');
            Route::get('seller-product-sale', 'SellerProductSaleReportController@index')->name('seller-product-sale');
        });
        Route::group(['prefix' => 'stock', 'as' => 'stock.', 'middleware' => ['module:business_section']], function () {
            //product stock report
            Route::get('product-stock', 'ProductStockReportController@index')->name('product-stock');
            Route::post('ps-filter', 'ProductStockReportController@filter')->name('ps-filter');
            //product in wishlist report
            Route::get('product-in-wishlist', 'ProductWishlistReportController@index')->name('product-in-wishlist');
            Route::post('piw-filter', 'ProductWishlistReportController@filter')->name('piw-filter');
        });
        Route::group(['prefix' => 'sellers', 'as' => 'sellers.', 'middleware' => ['module:user_section']], function () {
            Route::get('seller-list', 'SellerController@index')->name('seller-list');
            Route::get('order-list/{seller_id}', 'SellerController@order_list')->name('order-list');
            Route::get('product-list/{seller_id}', 'SellerController@product_list')->name('product-list');

            Route::get('order-details/{order_id}/{seller_id}', 'SellerController@order_details')->name('order-details');
            Route::get('verification/{id}', 'SellerController@view')->name('verification');
            Route::get('view/{id}/{tab?}', 'SellerController@view')->name('view');
            Route::post('update-status', 'SellerController@updateStatus')->name('updateStatus');
            Route::post('withdraw-status/{id}', 'SellerController@withdrawStatus')->name('withdraw_status');
            Route::get('withdraw_list', 'SellerController@withdraw')->name('withdraw_list');
            Route::get('withdraw-view/{withdraw_id}/{seller_id}', 'SellerController@withdraw_view')->name('withdraw_view');

            Route::post('sales-commission-update/{id}', 'SellerController@sales_commission_update')->name('sales-commission-update');
        });
        Route::group(['prefix' => 'request', 'as' => 'request.', 'middleware' => ['module:product_management']], function () {
            Route::get('list/{status}', 'RequestController@list')->name('list');
            Route::get('view/{id}', 'RequestController@view')->name('view');
            Route::get('approve-status', 'RequestController@approve_status')->name('approve-status');
            Route::get('denied', 'RequestController@denied')->name('denied');
        });
        Route::group(['prefix' => 'product', 'as' => 'product.', 'middleware' => ['module:product_management']], function () {
            Route::get('add-new', 'ProductController@add_new')->name('add-new');
            Route::post('store', 'ProductController@store')->name('store');
            Route::get('remove-image', 'ProductController@remove_image')->name('remove-image');
            Route::get('remove-label', 'ProductController@remove_label')->name('remove-label');
            Route::post('status-update', 'ProductController@status_update')->name('status-update');
            Route::get('list/{type}', 'ProductController@list')->name('list');
            //Route::get('list/{type}/{slug}', 'ProductController@list')->name('list');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::post('featured-status', 'ProductController@featured_status')->name('featured-status');
            Route::get('approve-status', 'ProductController@approve_status')->name('approve-status');
            Route::post('deny', 'ProductController@deny')->name('deny');
            Route::post('sku-combination', 'ProductController@sku_combination')->name('sku-combination');
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');
            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');

            Route::get('view/{id}', 'ProductController@view')->name('view');
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');
        });

        Route::group(['prefix' => 'transaction', 'as' => 'transaction.', 'middleware' => ['module:business_section']], function () {
            Route::get('list', 'TransactionController@list')->name('list');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'middleware' => ['module:business_settings']], function () {
            Route::get('general-settings', 'BusinessSettingsController@index')->name('general-settings');
            Route::get('update-language', 'BusinessSettingsController@update_language')->name('update-language');
            Route::get('about-us', 'BusinessSettingsController@about_us')->name('about-us');
            Route::post('about-us', 'BusinessSettingsController@about_usUpdate')->name('about-update');
            Route::post('update-info', 'BusinessSettingsController@updateInfo')->name('update-info');
            //Social Icon
            Route::get('social-media', 'BusinessSettingsController@social_media')->name('social-media');
            Route::get('fetch', 'BusinessSettingsController@fetch')->name('fetch');
            Route::post('social-media-store', 'BusinessSettingsController@social_media_store')->name('social-media-store');
            Route::post('social-media-edit', 'BusinessSettingsController@social_media_edit')->name('social-media-edit');
            Route::post('social-media-update', 'BusinessSettingsController@social_media_update')->name('social-media-update');
            Route::post('social-media-delete', 'BusinessSettingsController@social_media_delete')->name('social-media-delete');
            Route::post('social-media-status-update', 'BusinessSettingsController@social_media_status_update')->name('social-media-status-update');

            Route::get('terms-condition', 'BusinessSettingsController@terms_condition')->name('terms-condition');
            Route::get('terms-condition-dropship', 'BusinessSettingsController@terms_condition_dropship')->name('terms-condition-dropship');
            Route::post('terms-condition', 'BusinessSettingsController@updateTermsCondition')->name('update-terms');
            Route::post('terms-condition-dropship', 'BusinessSettingsController@updateTermsConditionDropship')->name('update-terms-dropship');
            Route::get('privacy-policy', 'BusinessSettingsController@privacy_policy')->name('privacy-policy');
            Route::get('contact-us', 'BusinessSettingsController@contact_us')->name('contact-us');
            Route::post('contact-us', 'BusinessSettingsController@update_contact_us')->name('update-contact-us');
            Route::post('privacy-policy', 'BusinessSettingsController@privacy_policy_update')->name('privacy-policy');

            Route::get('fcm-index', 'BusinessSettingsController@fcm_index')->name('fcm-index');
            Route::post('update-fcm', 'BusinessSettingsController@update_fcm')->name('update-fcm');

            Route::post('update-fcm-messages', 'BusinessSettingsController@update_fcm_messages')->name('update-fcm-messages');

            Route::group(['prefix' => 'shipping-method', 'as' => 'shipping-method.', 'middleware' => ['module:business_settings']], function () {
                Route::get('by/admin', 'ShippingMethodController@index_admin')->name('by.admin');
                //Route::get('by/seller', 'ShippingMethodController@index_seller')->name('by.seller');
                Route::post('add', 'ShippingMethodController@store')->name('add');
                Route::get('edit/{id}', 'ShippingMethodController@edit')->name('edit');
                Route::put('update/{id}', 'ShippingMethodController@update')->name('update');
                Route::post('delete', 'ShippingMethodController@delete')->name('delete');
                Route::post('status-update', 'ShippingMethodController@status_update')->name('status-update');
                Route::get('setting', 'ShippingMethodController@setting')->name('setting');
                Route::post('shipping-store', 'ShippingMethodController@shippingStore')->name('shipping-store');
            });

            Route::group(['prefix' => 'language', 'as' => 'language.', 'middleware' => ['module:business_settings']], function () {
                Route::get('', 'LanguageController@index')->name('index');
//                Route::get('app', 'LanguageController@index_app')->name('index-app');
                Route::post('add-new', 'LanguageController@store')->name('add-new');
                Route::get('update-status', 'LanguageController@update_status')->name('update-status');
                Route::get('update-default-status', 'LanguageController@update_default_status')->name('update-default-status');
                Route::post('update', 'LanguageController@update')->name('update');
                Route::get('translate/{lang}', 'LanguageController@translate')->name('translate');
                Route::post('translate-submit/{lang}', 'LanguageController@translate_submit')->name('translate-submit');
                Route::post('remove-key/{lang}', 'LanguageController@translate_key_remove')->name('remove-key');
                Route::get('delete/{lang}', 'LanguageController@delete')->name('delete');
            });

            Route::group(['prefix' => 'mail', 'as' => 'mail.', 'middleware' => ['module:web_&_app_settings']], function () {
                Route::get('', 'MailController@index')->name('index');
                Route::post('update', 'MailController@update')->name('update');
            });

            Route::group(['prefix' => 'address', 'as' => 'address.', 'middleware' => ['module:web_&_app_settings']], function () {
                Route::get('/', 'AddressController@index')->name('index');
                Route::post('update', 'AddressController@update')->name('update');
            });

            Route::group(['prefix' => 'web-config', 'as' => 'web-config.', 'middleware' => ['module:web_&_app_settings']], function () {
                Route::get('/', 'BusinessSettingsController@companyInfo')->name('index');
                Route::post('update-colors', 'BusinessSettingsController@update_colors')->name('update-colors');
                Route::post('update-language', 'BusinessSettingsController@update_language')->name('update-language');
                Route::post('update-company', 'BusinessSettingsController@updateCompany')->name('company-update');
                Route::post('update-company-email', 'BusinessSettingsController@updateCompanyEmail')->name('company-email-update');
                Route::post('update-company-phone', 'BusinessSettingsController@updateCompanyPhone')->name('company-phone-update');
                Route::post('upload-web-logo', 'BusinessSettingsController@uploadWebLogo')->name('company-web-logo-upload');
                Route::post('upload-mobile-logo', 'BusinessSettingsController@uploadMobileLogo')->name('company-mobile-logo-upload');
                Route::post('upload-footer-log', 'BusinessSettingsController@uploadFooterLog')->name('company-footer-logo-upload');
                Route::post('upload-fav-icon', 'BusinessSettingsController@uploadFavIcon')->name('company-fav-icon');
                Route::post('update-company-copyRight-text', 'BusinessSettingsController@updateCompanyCopyRight')->name('company-copy-right-update');
                Route::post('app-store/{name}', 'BusinessSettingsController@update')->name('app-store-update');
                Route::get('currency-symbol-position/{side}', 'BusinessSettingsController@currency_symbol_position')->name('currency-symbol-position');
                Route::post('shop-banner', 'BusinessSettingsController@shop_banner')->name('shop-banner');
            });
            Route::group(['prefix' => 'seller-settings', 'as' => 'seller-settings.', 'middleware' => ['module:business_settings']], function () {
                Route::get('/', 'BusinessSettingsController@seller_settings')->name('index');
                Route::post('update-seller-settings', 'BusinessSettingsController@sales_commission')->name('update-seller-settings');
                Route::post('update-seller-registration', 'BusinessSettingsController@seller_registration')->name('update-seller-registration');
            });

            Route::group(['prefix' => 'payment-method', 'as' => 'payment-method.', 'middleware' => ['module:business_settings']], function () {
                Route::get('/', 'PaymentMethodController@index')->name('index');
                Route::post('{name}', 'PaymentMethodController@update')->name('update');
            });

            Route::get('sms-module', 'SMSModuleController@sms_index')->name('sms-module');
            Route::post('sms-module-update/{sms_module}', 'SMSModuleController@sms_update')->name('sms-module-update');
        });
        //order management
        Route::group(['prefix' => 'orders', 'as' => 'orders.', 'middleware' => ['module:order_management']], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::post('status', 'OrderController@status')->name('status');
            Route::post('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::get('generate-invoicen/{id}', 'OrderController@generate_invoicess')->name('generate-invoicen');
            Route::get('inhouse-order-filter', 'OrderController@inhouse_order_filter')->name('inhouse-order-filter');
            Route::post('prints', 'OrderController@prints')->name('prints');
        });

        Route::group(['prefix' => 'resi', 'as' => 'resi.', 'middleware' => ['module:order_management']], function () {
            Route::get('/{status}/{user_is}', 'ResiController@index')->name('index');
            Route::post('status', 'ResiController@status')->name('status');
        });

        Route::group(['prefix' => 'orders-dropship', 'as' => 'orders-dropship.', 'middleware' => ['module:order_management']], function () {
            Route::get('list/{status}', 'OrderController@listDropship')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::post('status', 'OrderController@status')->name('status');
            Route::post('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::get('inhouse-order-filter', 'OrderController@inhouse_order_filter')->name('inhouse-order-filter');
        });

        Route::group(['prefix' => 'helpTopic', 'as' => 'helpTopic.', 'middleware' => ['module:web_&_app_settings']], function () {
            Route::get('list', 'HelpTopicController@list')->name('list');
            Route::post('add-new', 'HelpTopicController@store')->name('add-new');
            Route::get('status/{id}', 'HelpTopicController@status');
            Route::get('edit/{id}', 'HelpTopicController@edit');
            Route::post('update/{id}', 'HelpTopicController@update');
            Route::post('delete', 'HelpTopicController@destroy')->name('delete');
        });

        Route::group(['prefix' => 'contact', 'as' => 'contact.', 'middleware' => ['module:support_section']], function () {
            Route::post('contact-store', 'ContactController@store')->name('store');
            Route::get('list', 'ContactController@list')->name('list');
            Route::post('delete', 'ContactController@destroy')->name('delete');
            Route::get('view/{id}', 'ContactController@view')->name('view');
            Route::post('update/{id}', 'ContactController@update')->name('update');
            Route::post('send-mail/{id}', 'ContactController@send_mail')->name('send-mail');
        });
    });

    //for test

    /*Route::get('login', 'testController@login')->name('login');*/
});
