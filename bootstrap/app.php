<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', \App\Http\Middleware\SecureUpload::class])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin/admin.php'));

            Route::middleware(['web', \App\Http\Middleware\SecureUpload::class])
                ->group(base_path('routes/food.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
                $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('tickets:autoclose')->daily();
    })->create();
