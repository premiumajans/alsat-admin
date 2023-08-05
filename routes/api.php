<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CityController as City;
use App\Http\Controllers\Api\SalaryController as Salary;
use App\Http\Controllers\Api\EducationController as Education;
use App\Http\Controllers\Api\ExperienceController as Experience;
use App\Http\Controllers\Api\CategoryController as Category;
use App\Http\Controllers\Api\SettingController as Setting;
use App\Http\Controllers\Api\ModeController as Mode;
use App\Http\Controllers\Api\VacancyController as Vacancy;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::get('term', [\App\Http\Controllers\Api\UserController::class, 'term']);
Route::post('/', [\App\Http\Controllers\Api\DocumentationController::class, 'index'])->name('index');


Route::get('/message', [\App\Http\Controllers\Api\MessageController::class, 'message']);

Route::get('/get-company', [\App\Http\Controllers\Api\CompanyController::class, 'index']);
Route::post('/company-update', [\App\Http\Controllers\Api\CompanyController::class, 'update']);
Route::post('/company/update/photo', [\App\Http\Controllers\Api\CompanyController::class, 'updatePhoto']);
Route::post('/company/{id}/get-premium', [\App\Http\Controllers\Api\CompanyController::class, 'premium']);
Route::post('/company/{id}/extend/time', [\App\Http\Controllers\Api\CompanyController::class, 'extendPremium']);
Route::post('/company/{id}/premium/cancel', [\App\Http\Controllers\Api\CompanyController::class, 'cancelPremium']);

Route::get('/vacancies', [\App\Http\Controllers\Api\VacancyController::class, 'index']);
Route::get('/vacancies/all', [\App\Http\Controllers\Api\VacancyController::class, 'all']);
Route::get('/vacancies/{id}', [\App\Http\Controllers\Api\VacancyController::class, 'show']);
Route::post('/vacancies/{id}/update', [\App\Http\Controllers\Api\VacancyController::class, 'update']);
Route::post('/vacancies/{id}/delete', [\App\Http\Controllers\Api\VacancyController::class, 'deleteVacancy']);
Route::get('/vacancy/count', [\App\Http\Controllers\Api\VacancyController::class, 'count']);
Route::get('/my-items/', [\App\Http\Controllers\Api\VacancyController::class, 'myItems']);



//Wishlist
Route::get('wishlist/{id}/add', [\App\Http\Controllers\Api\WishlistController::class, 'add']);
Route::get('wishlist/{id}/remove', [\App\Http\Controllers\Api\WishlistController::class, 'remove']);
Route::get('wishlist/items', [\App\Http\Controllers\Api\WishlistController::class, 'items']);
//Feedback
Route::post('feedback', [\App\Http\Controllers\Api\FeedbackController::class, 'store']);


Route::group(['prefix' => '/', 'as' => 'api.'], function () {
    Route::resource('settings', Setting::class)->only(['index', 'show']);
});

Route::resource('salaries', Salary::class)->only(['index', 'show']);
Route::resource('education', Education::class)->only(['index', 'show']);
Route::resource('experience', Experience::class)->only(['index', 'show']);
Route::resource('categories', Category::class)->only(['index', 'show']);
Route::resource('modes', Mode::class)->only(['index', 'show']);
Route::resource('vacancies', Vacancy::class)->only(['index', 'show']);
Route::resource('city', City::class)->only(['index', 'show']);

//Adverts
Route::get('/adverts', [\App\Http\Controllers\Api\AdvertController::class, 'index']);
Route::post('/adverts/store', [\App\Http\Controllers\Api\AdvertController::class, 'store']);
Route::get('/adverts/all', [\App\Http\Controllers\Api\AdvertController::class, 'all']);
Route::get('/adverts/vip', [\App\Http\Controllers\Api\AdvertController::class, 'vip']);
Route::get('/adverts/vip/{id}', [\App\Http\Controllers\Api\AdvertController::class, 'toVip']);

Route::get('/my-adverts', [\App\Http\Controllers\Api\AdvertController::class, 'adverts']);
Route::get('/advert/{id}/premium', [\App\Http\Controllers\Api\AdvertController::class, 'premium']);
//Route::get('/advert/{id}/vip', [\App\Http\Controllers\Api\AdvertController::class, 'vip']);
Route::post('/advert/{id}/update', [\App\Http\Controllers\Api\AdvertController::class, 'update']);
Route::post('/advert/{id}/delete', [\App\Http\Controllers\Api\AdvertController::class, 'delete']);
Route::get('/advert/count', [\App\Http\Controllers\Api\AdvertController::class, 'count']);

Route::group(['prefix' => '/vacancies'], function () {
    Route::post('/store', [Vacancy::class, 'store']);
});

Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\UserController::class, 'register']);
    Route::post('/logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\Api\UserController::class, 'refresh']);
    Route::post('/check-user', [\App\Http\Controllers\Api\UserController::class, 'check']);
    Route::post('/change-password', [\App\Http\Controllers\Api\UserController::class, 'changePassword']);
    Route::post('/forgot-password', [\App\Http\Controllers\Api\UserController::class, 'forgotPassword']);
    Route::post('/reset-password', [\App\Http\Controllers\Api\UserController::class, 'resetPassword']);
    Route::get('/login/google', [\App\Http\Controllers\Api\UserController::class, 'redirectToGoogle'])->name('loginGoogle');
    Route::get('/login/google/callback', [\App\Http\Controllers\Api\UserController::class, 'handleGoogleCallback'])->name('loginGoogleCallback');
    Route::get('/login/facebook', [\App\Http\Controllers\Api\UserController::class, 'redirectToFacebook'])->name('loginFacebook');
    Route::get('/login/facebook/callback', [\App\Http\Controllers\Api\UserController::class, 'handleFacebookCallback'])->name('loginFacebookCallback');
});
