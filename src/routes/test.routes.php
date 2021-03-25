<?php

use Illuminate\Support\Facades\Route;

Route::prefix('rpc-gateway')
    ->group(static function () {
        Route::get(
            'test-method',
            static function () {
                return response()
                    ->json([
                        'rpc' => false,
                        'is_adapted' => false
                    ]);
            })
            ->name('rpc.gateway::test_method');
    });