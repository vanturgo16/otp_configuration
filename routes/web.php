<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MstCompaniesController;
use App\Http\Controllers\MstProvincesController;
use App\Http\Controllers\MstCountriesController;
use App\Http\Controllers\MstCurrenciesController;

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

//Route Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");

//Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store');
    Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    //Company
    Route::get('/company', [MstCompaniesController::class, 'index'])->name('company.index');
    Route::post('company/create', [MstCompaniesController::class, 'store'])->name('company.store');
    Route::post('company/update/{id}', [MstCompaniesController::class, 'update'])->name('company.update');

    //Province
    Route::get('/province', [MstProvincesController::class, 'index'])->name('province.index');
    Route::post('province/create', [MstProvincesController::class, 'store'])->name('province.store');
    Route::post('province/update/{id}', [MstProvincesController::class, 'update'])->name('province.update');
    Route::post('province/activate/{id}', [MstProvincesController::class, 'activate'])->name('province.activate');
    Route::post('province/deactivate/{id}', [MstProvincesController::class, 'deactivate'])->name('province.deactivate');

    //Country
    Route::get('/country', [MstCountriesController::class, 'index'])->name('country.index');
    Route::post('country/create', [MstCountriesController::class, 'store'])->name('country.store');
    Route::post('country/update/{id}', [MstCountriesController::class, 'update'])->name('country.update');
    Route::post('country/activate/{id}', [MstCountriesController::class, 'activate'])->name('country.activate');
    Route::post('country/deactivate/{id}', [MstCountriesController::class, 'deactivate'])->name('country.deactivate');

    //Currency
    Route::get('/currency', [MstCurrenciesController::class, 'index'])->name('currency.index');
    Route::post('currency/create', [MstCurrenciesController::class, 'store'])->name('currency.store');
    Route::post('currency/update/{id}', [MstCurrenciesController::class, 'update'])->name('currency.update');
    Route::post('currency/activate/{id}', [MstCurrenciesController::class, 'activate'])->name('currency.activate');
    Route::post('currency/deactivate/{id}', [MstCurrenciesController::class, 'deactivate'])->name('currency.deactivate');
    
    //Audit Log
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog');
});

