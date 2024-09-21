<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use Illuminate\Support\Facades\Route;



    Route::name('user.')->group(function() {




        Route::middleware(['auth'])->group(function () {

            Route::get('/home', [HomeController::class, 'index'])->name('home');


            Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


            Route::controller(ClientController::class)->group(function(){

                Route::get('/add-to-cart','AddToCart')->name('addtocart');
                Route::post('/add-product-to-cart','AddProductToCart')->name('addproducttocart');
                Route::get('/shipping-address','GetShippingAddress')->name('shippingaddress');
                Route::post('/add-shipping-info','AddShipingInfo')->name('addshippinginfo');
                Route::post('/place-order','PlaceOrder')->name('placeorder');

                Route::get('/checkout','Checkout')->name('checkout');
                Route::get('/user-profile','UserProfile')->name('userprofile');
                Route::get('/user-profile/pending-orders','PendingOrders')->name('pendingorders');
                Route::get('/user-profile/history','History')->name('history');

                Route::get('/todays-deal','TodaysDeal')->name('todaysdeal');
                Route::get('/customer-service','CustomerService')->name('customerservice');
                Route::get('/remove-cart-item/{id}','RemoveCartItem')->name('removeitem');
            });

        });
    });
Auth::routes();
