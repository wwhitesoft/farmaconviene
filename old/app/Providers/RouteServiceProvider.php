<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth'])
                ->prefix('admin')
                ->group(function() {
                    Route::match(['get', 'post'], 'jqadm/{site?}', 
                        '\Aimeos\Shop\Controller\JqadmController@indexAction')
                        ->name('aimeos_shop_jqadm');
                    Route::match(['get', 'post'], 'jqadm/file/{site?}', 
                        '\Aimeos\Shop\Controller\JqadmController@fileAction');
                });
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}