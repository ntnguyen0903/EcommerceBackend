<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\VerificationController;

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

Route::post('register', [AuthController::class, 'register']);


Route::post('login', [AuthController::class, 'login']);
// test
// //changePassword
// Route::post('changePassword', [AuthController::class, 'changePassword']);
// //
// Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset');
// Route::get('/activate-account/{token}', [AuthController::class, 'activateAccount']);
// Route::get('/activate-account', [AuthController::class, 'activateAccount']);

// Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
//statistics

//loại sản phẩm
Route::get('getCategory', [FrontendController::class, 'category']);
Route::get('fetchproducts/{slug}', [FrontendController::class, 'product']);

//product user theo tất cả sản phẩm 
Route::get('view-producttest', [FrontendController::class, 'index']);
//product user theo  sản phẩm bán chạy Bestseller view-product-bestseller
Route::get('view-product-bestseller', [FrontendController::class, 'indexbestseller']);
//product user theo  sản phẩm mới
Route::get('view-product-new', [FrontendController::class, 'indexnew']);
// tìm kiếm
Route::get('/search', [FrontendController::class, 'search']);
//chi tiết sản phẩm
Route::get('viewProductDetail/{category_slug}/{product_slug}', [FrontendController::class, 'viewproduct']);
Route::post('add-to-cart', [CartController::class, 'addtocart']);
Route::get('cart', [CartController::class, 'viewcart']);
Route::put('cart-updatequantity/{cart_id}/{scope}', [CartController::class, 'updatequantity']);
Route::delete('delete-cartitem/{cart_id}', [CartController::class, 'deleteCartitem']);
//send STML đăng ký
// Route::post('/send-activation-email', [AuthController::class, 'sendActivationEmail']);
// Route::get('/activate-account/{token}', [AuthController::class, 'activateAccount']);
//đơn hàng  KH
Route::get('view-orderuser', [FrontendController::class, 'vieworderuser']);
//sửa
Route::put('/cancel-order/{id}', [OrderController::class, 'cancelOrder']);
//vieworderusersĐang xử lývieworderuserstatus0
Route::get('view-orderuser0', [FrontendController::class, 'vieworderuserstatus0']);
//vieworderuserĐã xác nhận
Route::get('view-orderuser5', [FrontendController::class, 'vieworderuserstatus5']);
//vieworderusers Đang giao
Route::get('view-orderuser1', [FrontendController::class, 'vieworderuserstatus1']);
//vieworderusers Đã giao
Route::get('view-orderuser2', [FrontendController::class, 'vieworderuserstatus2']);
//vieworderuserĐã hủy
Route::get('view-orderuser3', [FrontendController::class, 'vieworderuserstatus3']);
//đặt hàng
Route::post('place-order', [CheckoutController::class, 'placeorder']);
Route::post('validate-order', [CheckoutController::class, 'validateOrder']);

//Quản  lý thông tin cá nhân view  edit update
Route::get('view-indexuser', [AuthController::class, 'indexUser']);
Route::put('/update-user', [AuthController::class, 'updateUser']);
Route::post('/change-password',[AuthController::class,'change_password'])->middleware('auth:sanctum');
//gửi email khi quên mk
Route::post('/forgot-password',[ForgotPasswordController::class,'sendResetLinkEmail']);

//isAPIAdmin
Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function () {
    // Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message' => 'You are in', 'status' => '200'], 200);
    });
    //category index
    Route::get('view-category', [CategoryController::class, 'index']);
    Route::post('store-category', [CategoryController::class, 'store']);
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::put('update-category/{id}', [CategoryController::class, 'update']);
    Route::delete('delete-category/{id}', [CategoryController::class, 'destroy']);
    Route::get('all-category', [CategoryController::class, 'allcategory']);
    //product edit-product update-product
    Route::post('store-product', [ProductController::class, 'store']);
    Route::get('view-product', [ProductController::class, 'index']);
    Route::get('edit-product/{id}', [ProductController::class, 'edit']);
    Route::post('update-product/{id}', [ProductController::class, 'update']);
    Route::delete('delete-product/{id}', [ProductController::class, 'destroy']);
    // tìm kiếm
    Route::get('/searchSP', [FrontendController::class, 'search']);
 
    //orderview-orders
    Route::get('view-orders', [OrderController::class, 'index']);
    Route::get('edit-order/{id}', [OrderController::class, 'edit']);
    Route::get('chitiet-order/{id}', [OrderController::class, 'edit1']);
    Route::put('update-order/{id}', [OrderController::class, 'update']);
   //thống kê
   Route::get('statistics', [OrderController::class, 'statistics']);
   Route::get('/searchOrder', [OrderController::class, 'search']);
    //

    //countOrder
    Route::get('count-Order', [OrderController::class, 'countOrder']);
    // user
    Route::get('view-user', [AuthController::class, 'index']);
    Route::get('edit-user/{id}', [AuthController::class, 'edit']);
    Route::put('update-user/{id}', [AuthController::class, 'update']);
    Route::delete('delete-user/{id}', [AuthController::class, 'destroy']);
    //  tìm Khach hàng
    
    Route::get('/searchKH', [AuthController::class, 'search']);

});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
// Route::middleware('auth:sanctum')->post('/logout', 'App\Http\Controllers\AuthController@logout');
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


