<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\CustomerRegisterController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\StripePaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Frontend Controller
Route::get('/', [FrontendController::class, 'home'])->name('site');
Route::get('/product/details/{slug}', [FrontendController::class, 'product_details'])->name('product.details');
Route::post('/getSize', [FrontendController::class, 'getSize']);
Route::get('/category/product/{category_id}', [FrontendController::class, 'category_product'])->name('category.product');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User Controller
Route::get('/user/list', [UserController::class, 'users'])->name('users');
Route::get('/user/delete/{user_id}', [UserController::class, 'user_delete'])->name('user.delete');

// Profile(User)
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/profile/name/update', [UserController::class, 'profile_name_update'])->name('name.update');
Route::post('/profile/password/update', [UserController::class, 'profile_password_update'])->name('password.update');
Route::post('/profile/image/update', [UserController::class, 'profile_image_update'])->name('image.update');

// Category
Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::post('/category/add', [CategoryController::class, 'add_category'])->name('add.category');
Route::get('/category/soft/delete/{category_id}', [CategoryController::class, 'category_soft_delete'])->name('category.delete');
Route::get('/category/force/delete/{category_id}', [CategoryController::class, 'category_force_delete'])->name('category.force.delete');
Route::get('/category/edit/{category_id}', [CategoryController::class, 'category_edit'])->name('category.edit');
Route::post('/category/update', [CategoryController::class, 'category_update'])->name('category.update');

// Subcategory
Route::get('/subcategory', [SubcategoryController::class, 'subcategory'])->name('subcategory');
Route::post('/subcategory/add', [SubcategoryController::class, 'add_subcategory'])->name('add.subcategory');
Route::get('/subcategory/soft/delete/{subcategory_id}', [SubcategoryController::class, 'subcategory_soft_delete'])->name('subcategory.delete');
Route::get('/subcategory/restore/{subcategory_id}', [SubcategoryController::class, 'subcategory_restore'])->name('subcategory.restore');
Route::get('/subcategory/edit/{subcategory_id}', [SubcategoryController::class, 'subcategory_edit'])->name('subcategory.edit');
Route::get('/subcategory/force/delete/{subcategory_id}', [SubcategoryController::class, 'subcategory_force_delete'])->name('subcategory.force.delete');
Route::post('/subcategory/update/', [SubcategoryController::class, 'subcategory_update'])->name('update.subcategory');

// Product
Route::get('/product', [ProductController::class, 'product'])->name('product');
Route::post('/getSubcategory', [ProductController::class, 'getSubcategory']);
Route::post('/product/store', [ProductController::class, 'product_store'])->name('product.store');
Route::get('/product/list', [ProductController::class, 'product_list'])->name('product.list');
Route::get('/product/delete/{product_id}', [ProductController::class, 'product_delete'])->name('product.delete');

// Brand
Route::get('/brand', [ProductController::class, 'brand'])->name('brand');
Route::post('/add/brand', [ProductController::class, 'add_brand'])->name('add.brand');

// Product Inventory
Route::get('/product/inventory/{product_id}', [ProductController::class, 'inventory'])->name('inventory');
Route::post('/product/inventory/store', [ProductController::class, 'inventory_store'])->name('inventory.store');
Route::get('/product/inventory/delete/{product_id}', [ProductController::class, 'inventory_delete'])->name('inventory.delete');


// Product Variation
Route::get('/product/variation', [ProductController::class, 'product_variation'])->name('product.variation');
Route::post('/product/variation/color', [ProductController::class, 'add_color'])->name('add.color');
Route::post('/product/variation/size', [ProductController::class, 'add_size'])->name('add.size');
Route::get('/product/variation/color/delete/{color_id}', [ProductController::class, 'color_delete'])->name('color.delete');
Route::get('/product/variation/size/delete/{size_id}', [ProductController::class, 'size_delete'])->name('size.delete');

// Customer Authentication
Route::get('/customer/authentication', [FrontendController::class, 'customer_authentication'])->name('customer.authentication');
Route::post('/customer/register', [CustomerRegisterController::class, 'customer_register'])->name('customer.register');
Route::post('/customer/login', [CustomerLoginController::class, 'customer_login'])->name('customer.login');
Route::get('/customer/logout', [CustomerLoginController::class, 'customer_logout'])->name('customer.logout');
Route::get('/customer/profile', [CustomerLoginController::class, 'customer_profile'])->name('customer.profile')->middleware('customerauth');
Route::get('/customer/order', [CustomerController::class, 'customer_order'])->name('customer.order');
Route::post('/customer/profile/update', [CustomerLoginController::class, 'customer_profile_update'])->name('customer.profile.update');

// Cart 
Route::post('/cart/store', [CartController::class, 'cart_store'])->name('cart.store');
Route::get('/cart/remove/{cart_id}', [CartController::class, 'cart_remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'cart_clear'])->name('cart.clear');
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cart/update', [CartController::class, 'cart_update'])->name('cart.update');

// Wishlist
Route::get('/wishlist/remove/{wishlist_id}', [WishlistController::class, 'wishlist_remove'])->name('wishlist.remove');
Route::get('/wishlist/clear', [WishlistController::class, 'wishlist_clear'])->name('wishlist.clear');
Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('wishlist');
// Route::get('/cart/clear', [CartController::class, 'cart_clear'])->name('cart.clear');

// Coupon
Route::get('/coupon', [CouponController::class, 'coupon'])->name('coupon');
Route::post('/coupon/store', [CouponController::class, 'coupon_store'])->name('coupon.store');
Route::get('/coupon/delete/{coupon_id}', [CouponController::class, 'coupon_delete'])->name('coupon.delete');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/getCity', [CheckoutController::class, 'getCity'])->name('getCity');
Route::post('/getNumber', [CheckoutController::class, 'getNumber'])->name('getNumber');

// Orders
Route::post('/order/store', [OrderController::class, 'order_store'])->name('order.store');
Route::get('/order/success', [OrderController::class, 'order_success'])->name('order.success');
Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
Route::post('/order/status', [OrderController::class, 'order_status'])->name('order.status');

// Invoice
Route::get('/download/invoice/{order_id}', [OrderController::class, 'download_invoice'])->name('download.invoice');

// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::get('/pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

Route::controller(StripePaymentController::class)->group(function(){
    Route::get('stripe', 'stripe')->name('stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');
});

// Review
Route::post('/review/store', [CustomerController::class, 'review_store'])->name('review.store');

// Role manager
Route::get('/role', [RoleController::class, 'role'])->name('role');
Route::post('/add/permission', [RoleController::class, 'add_permission'])->name('add.permission');
Route::post('/add/role', [RoleController::class, 'add_role'])->name('add.role');
Route::post('/asign/role', [RoleController::class, 'asign_role'])->name('asign.role');
Route::get('/remove/role/{user_id}', [RoleController::class, 'remove_role'])->name('remove.user.role');
Route::get('/edit/role/{role_id}', [RoleController::class, 'edit_role'])->name('role.edit');
Route::post('/update/role/', [RoleController::class, 'update_role'])->name('update.role');

// Customer Password Reset
Route::get('/customer/password/reset/req', [CustomerController::class, 'customer_pass_reset_req'])->name('customer.pass.reset.req');
Route::post('/customer/password/reset/send', [CustomerController::class, 'customer_pass_reset_send'])->name('customer.pass.reset.send');
Route::get('/customer/password/reset/form/{token}', [CustomerController::class, 'customer_pass_reset_form'])->name('customer.pass.reset.form');
Route::post('/cus/password/reset/set', [CustomerController::class, 'cus_password_reset_set'])->name('cus.password.reset.set');

// Customer Email Verify
Route::get('/customer/email/verify/{token}', [CustomerRegisterController::class, 'customer_email_verify'])->name('customer.email.verify');

// Social Authentication Controller
Route::get('/github/redirect', [GithubController::class, 'github_redirect'])->name('github.redirect');
Route::get('/github/callback', [GithubController::class, 'github_callback'])->name('github.callback');

Route::get('/google/redirect', [GoogleController::class, 'google_redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'google_callback'])->name('google.callback');

Route::get('/facebook/redirect', [FacebookController::class, 'facebook_redirect'])->name('facebook.redirect');
Route::get('/facebook/callback', [FacebookController::class, 'facebook_callback'])->name('facebook.callback');
