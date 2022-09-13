<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;



Route::name('admin.')
    ->middleware(
        [
            'auth',
            config('jetstream.auth_session'),
            'verified',
        ]
    )
    ->group(function () {

        Route::prefix('dashboard')
            ->name('dashboard.')
            ->controller(DashboardController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });

        Route::prefix('users')
            ->name('user.')
            ->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
        Route::prefix('products')
            ->name('product.')
            ->controller(ProductController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('edit', 'edit')->name('edit');
                Route::post('update/', 'update')->name('update');
            });
    });
