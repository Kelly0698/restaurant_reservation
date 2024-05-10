<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\HolidayController;
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

// Route::group(['middleware' => 'restaurant_auth'], function () {
//     // Define your restaurant routes or controllers here
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[UserController::class, 'adminDashboard'])->name('dashboard');
Route::get('/home',[UserController::class, 'userDashboard'])->name('home');

//Role
Route::get('/role',[RoleController::class, 'index'])->name('role_list');
Route::delete('/delete/role/{role}',[RoleController::class, 'destroy'])->name('destroy_role');
Route::post('/create/role',[RoleController::class, 'create'])->name('create_role');
Route::get('/show/role/{id}',[RoleController::class, 'show'])->name('show_role');
Route::post('/edit/role/{role}',[RoleController::class, 'edit'])->name('edit_role');
Route::get('get/role/{id}', [RoleController::class,'getRole'])->name('get_role');
Route::post('/check/role', [RoleController::class, 'validateRoleForm'])->name('check_role');

//user_auth
Route::get('/login', function () {return view('login');})->name('login');  
Route::post('/login',[UserController::class, 'login']);
Route::get('/user/register',[UserController::class, 'userRegister'])->name('register_page');
Route::get('/logout',[UserController::class, 'logout'])->name('logout');
Route::get('/forget-password', [UserController::class, 'forgotPassword'])->name('forgot_password_page');

//restaurant_auth
Route::get('/res-tau-rant/logout',[RestaurantController::class, 'logout'])->name('res_logout');
Route::get('/your-restaurant', function () {return view('restaurant_login');})->name('restaurant_login_page');
Route::post('/restaurant-login',[RestaurantController::class, 'login'])->name('r_login');
Route::get('/register-restaurant',[RestaurantController::class, 'restaurantRegister'])->name('restaurant_register_page');
Route::post('/restaurant-register',[RestaurantController::class, 'create'])->name('register_restaurant');

//user
Route::get('/user',[UserController::class, 'index'])->name('user_list');
Route::post('/add/user',[UserController::class, 'adminCreate'])->name('add_user');
Route::post('/register/user',[UserController::class, 'create'])->name('register_user');
Route::post('/check/user', [UserController::class, 'validateUserForm'])->name('check_user');
Route::delete('/delete/user/{user}',[UserController::class, 'destroy'])->name('destroy_user');
Route::get('/show/user/{id}',[UserController::class, 'show'])->name('show_user');
Route::get('get/user/{id}', [UserController::class,'getUser'])->name('get_user');
Route::post('/edit/user/{user}',[UserController::class, 'edit'])->name('edit_user');
Route::post('/update-profile-pic/{user}',[UserController::class, 'updatePic'])->name('update_pic');
Route::get('/profile-user',[UserController::class, 'user_profile'])->name('user_profile');
Route::get('/view/restaurant/{id}', [UserController::class, 'viewRestaurant'])->name('view_restaurant');
Route::get('/get-ratings', [UserController::class, 'getRatings'])->name('get_ratings');

//restaurant
Route::get('/restaurant',[RestaurantController::class, 'index'])->name('restaurant_list');
Route::post('/add/restaurant',[RestaurantController::class, 'adminCreate'])->name('add_restaurant');
Route::get('/show/restaurant/{id}',[RestaurantController::class, 'show'])->name('show_restaurant');
Route::post('/edit/restaurant/{restaurant}',[RestaurantController::class, 'edit'])->name('edit_restaurant');
Route::delete('/delete/restaurant/{restaurant}',[RestaurantController::class, 'destroy'])->name('destroy_restaurant');
Route::get('/request',[RestaurantController::class, 'index_req'])->name('restaurant_req_list');
Route::post('/update-status/{id}',[RestaurantController::class, 'updateStatus'])->name('update_status');
Route::get('/your-profile',[RestaurantController::class, 'restaurant_profile'])->name('restaurant_profile');
Route::post('/logo-pic/update/{restaurant}',[RestaurantController::class, 'updateLogo'])->name('update_logo');
Route::post('/upload-picture', [RestaurantController::class, 'uploadPicture'])->name('upload_picture');
Route::delete('/pic-delete/{id}',[RestaurantController::class, 'deleteAttachment'])->name('pic_delete');

//reservation
Route::post('/reservation-make',[UserController::class, 'makeReservation'])->name('make_reservation');
Route::get('/record', [UserController::class, 'reservationRecord'])->name('reservation_record');
Route::post('/approve-reservation/{id}',[RestaurantController::class, 'approveReservation'])->name('approve_reservation');
Route::get('/reserve/approve', [RestaurantController::class, 'approveResPage'])->name('approve_page');
Route::post('/reject-reservation/{id}',[RestaurantController::class, 'rejectReservation'])->name('reject_reservation');
Route::get('/reserve/reject', [RestaurantController::class, 'rejectResPage'])->name('reject_page');
Route::post('/cancel-reservation/{id}', [UserController::class, 'cancelReservation'])->name('cancel_reservation');
Route::get('/pending/reservation', [UserController::class, 'pendingReservation'])->name('pending_reservation');
//rating
Route::post('/ratings', [RatingController::class, 'store'])->name('store_rating');


//holiday
Route::get('/calendar', [HolidayController::class, 'index'])->name('holiday');
Route::post('/add-holiday', [HolidayController::class, 'store'])->name('add_holiday');
Route::get('/holidays', [HolidayController::class, 'show'])->name('show_holiday');
Route::put('/update/holidays/{id}', [HolidayController::class, 'update'])->name('update_holiday');
Route::delete('/delete/holidays/{id}', [HolidayController::class, 'delete'])->name('delete_Holidays');
Route::get('/get-holidays', [HolidayController::class, 'getHoliday'])->name('get_holiday');
