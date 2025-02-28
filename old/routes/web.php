<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        return redirect('/admin/jqadm');
    });

    Route::match(['get', 'post'], '/admin/jqadm/{site?}', [\Aimeos\Shop\Controller\JqadmController::class, 'indexAction'])
        ->name('aimeos_shop_jqadm');
    Route::match(['get', 'post'], '/admin/jqadm/file/{site?}', [\Aimeos\Shop\Controller\JqadmController::class, 'fileAction']);
});