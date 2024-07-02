<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LikeController;

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
    return redirect('/login');
});


Route::get('/dashboard', [UserController::class, 'adminDashboard'])->name('dashboard');
Route::get('/home',[UserController::class, 'userDashboard'])->name('home')->middleware('auth');

//Role
Route::get('/role',[RoleController::class, 'index'])->name('role_list')->middleware('auth');
Route::delete('/delete/role/{role}',[RoleController::class, 'destroy'])->name('destroy_role')->middleware('auth');
Route::post('/create/role',[RoleController::class, 'create'])->name('create_role')->middleware('auth');
Route::get('/show/role/{id}',[RoleController::class, 'show'])->name('show_role')->middleware('auth');
Route::post('/edit/role/{role}',[RoleController::class, 'edit'])->name('edit_role')->middleware('auth');
Route::get('get/role/{id}', [RoleController::class,'getRole'])->name('get_role')->middleware('auth');
Route::post('/check/role', [RoleController::class, 'validateRoleForm'])->name('check_role')->middleware('auth');
Route::get('/search-roles', [RoleController::class, 'search'])->name('roles.search')->middleware('auth');

//user_auth
Route::get('/login', function () {return view('login');})->name('login');  
Route::post('/login',[UserController::class, 'login']);
Route::get('/user/register',[UserController::class, 'userRegister'])->name('register_page');
Route::get('/logout',[UserController::class, 'logout'])->name('logout');
Route::get('/password/forgot', [UserController::class, 'forgotPassword'])->name('forgot_password_page');
Route::post('/forgot-password', [UserController::class, 'ForgetPasswordStore'])->name('ForgetPasswordPost');

//restaurant_auth
Route::get('/res-tau-rant/logout',[RestaurantController::class, 'logout'])->name('res_logout');
Route::get('/your-restaurant', function () {return view('restaurant.restaurant_login');})->name('restaurant_login_page');
Route::post('/restaurant-login',[RestaurantController::class, 'login'])->name('r_login');
Route::get('/register-restaurant',[RestaurantController::class, 'restaurantRegister'])->name('restaurant_register_page');
Route::post('/restaurant-register',[RestaurantController::class, 'create'])->name('register_restaurant');
Route::get('/restaurant-forget-password', [RestaurantController::class, 'forgotPassword'])->name('restaurant_forgot_password_page');
Route::post('/restaurant-password', [RestaurantController::class, 'ForgetPasswordStore'])->name('restaurant_ForgetPasswordPost');

//user
Route::get('/user',[UserController::class, 'index'])->name('user_list')->middleware('auth');
Route::get('/search-users', [UserController::class, 'user_search'])->name('users.search')->middleware('auth');
Route::post('/add/user',[UserController::class, 'adminCreate'])->name('add_user')->middleware('auth');
Route::post('/register/user',[UserController::class, 'create'])->name('register_user')->middleware('auth');
Route::post('/check/user', [UserController::class, 'validateUserForm'])->name('check_user')->middleware('auth');
Route::delete('/delete/user/{user}',[UserController::class, 'destroy'])->name('destroy_user')->middleware('auth');
Route::get('/show/user/{id}',[UserController::class, 'show'])->name('show_user')->middleware('auth');
Route::get('get/user/{id}', [UserController::class,'getUser'])->name('get_user')->middleware('auth');
Route::post('/edit/user/{user}',[UserController::class, 'edit'])->name('edit_user')->middleware('auth');
Route::post('/update-profile-pic/{user}',[UserController::class, 'updatePic'])->name('update_pic')->middleware('auth');
Route::get('/profile-user',[UserController::class, 'user_profile'])->name('user_profile')->middleware('auth');
Route::get('/view/restaurant/{id}', [UserController::class, 'viewRestaurant'])->name('view_restaurant')->middleware('auth');
Route::get('/get-ratings', [UserController::class, 'getRatings'])->name('get_ratings')->middleware('auth');
Route::get('/search-restaurants', [UserController::class, 'search'])->name('search_restaurants')->middleware('auth');
Route::get('/user/password/reset', [UserController::class, 'showUserResetForm'])->name('showUserResetForm')->middleware('auth');
Route::post('/user/password/reset', [UserController::class, 'resetUserPassword'])->name('resetUserPasswordPost')->middleware('auth');

//restaurant
Route::get('/restaurant',[RestaurantController::class, 'index'])->name('restaurant_list')->middleware('auth');
Route::get('/search-restaurant-list', [RestaurantController::class, 'restaurant_search'])->name('restaurants.search')->middleware('auth');
Route::post('/add/restaurant',[RestaurantController::class, 'adminCreate'])->name('add_restaurant')->middleware('auth');
Route::get('/show/restaurant/{id}',[RestaurantController::class, 'show'])->name('show_restaurant')->middleware('auth');
Route::post('/edit/restaurant/{restaurant}',[RestaurantController::class, 'edit'])->name('edit_restaurant')->middleware('auth:restaurant');
Route::delete('/delete/restaurant/{restaurant}',[RestaurantController::class, 'destroy'])->name('destroy_restaurant')->middleware('auth');
Route::get('/request',[RestaurantController::class, 'index_req'])->name('restaurant_req_list')->middleware('auth');
Route::post('/update-status/{id}',[RestaurantController::class, 'updateStatus'])->name('update_status')->middleware('auth');
Route::get('/your-profile',[RestaurantController::class, 'restaurant_profile'])->name('restaurant_profile')->middleware('auth:restaurant');
Route::post('/logo-pic/update/{restaurant}',[RestaurantController::class, 'updateLogo'])->name('update_logo')->middleware('auth:restaurant');
Route::post('/upload-picture', [RestaurantController::class, 'uploadPicture'])->name('upload_picture')->middleware('auth:restaurant');
Route::delete('/pic-delete/{id}',[RestaurantController::class, 'deleteAttachment'])->name('pic_delete')->middleware('auth:restaurant');
Route::post('/check-email', [RestaurantController::class, 'checkEmail'])->name('check.email');
Route::post('/restaurant-check', [RestaurantController::class, 'checkEmailExistence'])->name('check_email');
Route::get('restaurant/password/reset', [RestaurantController::class, 'showResetForm'])->name('ResetPasswordPage')->middleware('auth:restaurant');
Route::post('restaurant/password/reset', [RestaurantController::class, 'resetPassword'])->name('ResetPasswordPost')->middleware('auth:restaurant');
Route::get('table/restaurant', [RestaurantController::class, 'restaurant_table'])->name('table_arrangement')->middleware('auth:restaurant');
Route::post('/upload-table-arrangement', [RestaurantController::class, 'upload_table_pic'])->name('table_arrangement_pic')->middleware('auth:restaurant');

//reservation
Route::post('/reservation-make',[UserController::class, 'makeReservation'])->name('make_reservation')->middleware('auth');
Route::get('/record', [UserController::class, 'reservationRecord'])->name('reservation_record');
Route::post('/approve-reservation/{id}', [RestaurantController::class, 'approveReservation'])->name('approve_reservation')->middleware('auth:restaurant');
Route::get('/reserve/approve', [RestaurantController::class, 'approveResPage'])->name('approve_page')->middleware('auth:restaurant');
Route::post('/update-completeness/{id}', [RestaurantController::class, 'updateCompleteness'])->name('update_completeness')->middleware('auth:restaurant');
Route::get('/done-reservations', [RestaurantController::class, 'showDoneReservations'])->name('done_reservations')->middleware('auth:restaurant');
Route::post('/reject-reservation/{id}',[RestaurantController::class, 'rejectReservation'])->name('reject_reservation')->middleware('auth:restaurant');
Route::get('/reserve/reject', [RestaurantController::class, 'rejectResPage'])->name('reject_page')->middleware('auth:restaurant');
Route::post('/cancel-reservation/{id}', [UserController::class, 'cancelReservation'])->name('cancel_reservation')->middleware('auth');
Route::get('/view-cancel-reservation',[UserController::class, 'viewCanceledReservations'])->name('view_cancel')->middleware('auth');
Route::get('/restaurant-view-cancel-reservation',[RestaurantController::class, 'CanceledReservations'])->name('restaurant_view_cancel')->middleware('auth:restaurant');
Route::get('/pending/reservation', [UserController::class, 'pendingReservation'])->name('pending_reservation');
Route::get('/available-tables', [UserController::class, 'getAvailableTables']);
Route::get('/absent-reserve', [RestaurantController::class, 'AbsentResPage'])->name('absent_page')->middleware('auth:restaurant');

//rating
Route::post('/ratings', [RatingController::class, 'store'])->name('store_rating')->middleware('auth');
Route::get('/get-user-phone-number/{userName}', [RestaurantController::class, 'getUserPhoneNumber'])->middleware('auth:restaurant');

//holiday
Route::get('/calendar', [HolidayController::class, 'index'])->name('holiday')->middleware('auth:restaurant');
Route::post('/add-holiday', [HolidayController::class, 'store'])->name('add_holiday')->middleware('auth:restaurant');
Route::get('/holidays', [HolidayController::class, 'show'])->name('show_holiday')->middleware('auth:restaurant');
Route::put('/update/holidays/{id}', [HolidayController::class, 'update'])->name('update_holiday')->middleware('auth:restaurant');
Route::delete('/delete/holidays/{id}', [HolidayController::class, 'delete'])->name('delete_Holidays')->middleware('auth:restaurant');
Route::get('/get-holidays', [HolidayController::class, 'getHoliday'])->name('get_holiday');

//saved_restaurant
Route::post('/save_restaurant/{id}', [LikeController::class, 'saveRestaurant'])->middleware('auth');
Route::get('/user/saved-restaurants', [LikeController::class, 'savedRestaurants'])->name('saved_restaurants')->middleware('auth');

