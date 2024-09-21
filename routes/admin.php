<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Admin

Route::name('admin.')->group(function() {

    Route::middleware(['admin.guest'])->group(function () {
        //Your routes here
        Route::get('/login', [Auth\LoginController ::class, 'showLoginForm'])->name('login');
        Route::post('/login', [Auth\LoginController ::class, 'login'])->name('signin');
    });
    Route::middleware(['admin'])->group(function () {
        //Dashboard
        // Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');


          //Profile
          Route::get('/profile', [AdminController::class, 'Profile'])->name('profile');
          Route::post('/profile',[AdminController::class,'profileUpdate'])->name('profile.update');

          //password
          Route::get('/change/password',[AdminController::class,'passwordChange'])->name('password');
          Route::post('password',[AdminController::class,'passwordUpdate'])->name('password.update');

          Route::controller(DashnboardController::class)->group(function(){
            Route::get('dashboard', 'index')->name('dashboard');
        });

        Route::controller(CategoryController::class)->group(function(){
            Route::get('admin/all-category', 'AllCategory')->name('allcategory');
            Route::get('admin/add-category', 'AddCategory')->name('addcategory');
            Route::post('admin/store-category', 'StoreCategory')->name('storecategory');
            Route::get('admin/edit-category/{id}','EditCategory')->name('editcategory');
            Route::post('admin/update-category', 'UpdateCategory')->name('updatecategory');
            Route::get('admin/delete-category/{id}','DdeleteCategory')->name('deletecategory');

        });

        Route::controller(SubCategoryController::class)->group(function(){
            Route::get('admin/all-subcategory', 'AllSubCategory')->name('allsubcategory');
            Route::get('admin/add-subcategory', 'AddSubCategory')->name('addsubcategory');
            Route::post('admin/store-subcategory', 'StoreSubCategory')->name('storesubcategory');
            Route::get('admin/edit-subcategory/{id}','EditSubCat')->name('editsubcategory');
            Route::post('admin/update-subcategory', 'UpdatesubCat')->name('updatesubcategory');
            Route::get('admin/delete-subcategory/{id}','DeleteSubCat')->name('deletesubcategory');




        });

        Route::controller(ProductController::class)->group(function(){
            Route::get('admin/all-products', 'AllProduct')->name('allproducts');
            Route::get('admin/add-product', 'AddProduct')->name('addproduct');
            Route::post('admin/store-product', 'StoreProduct')->name('storeproduct');
            Route::get('admin/edit-product-image/{id}','EditProductImg')->name('editproductimg');
            Route::post('admin/update-product-image', 'UpdateProductImage')->name('updateproductimage');
            Route::get('admin/edit-product/{id}', 'EditProduct')->name('editproduct');
            Route::post('admin/update-product', 'UpdateProduct')->name('updateproduct');
            Route::get('admin/delete-product/{id}','DeleteProduct')->name('deleteproduct');


        });



        Route::controller(OrderController::class)->group(function(){
            Route::get('admin/prnding-order', 'PendingOrder')->name('pendingorder');

        });



        //logout
        Route::get('/logout', [Auth\LoginController ::class, 'logout'])->name('logout');
    });

});


