<?php

use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\JobTitleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', function () {
    $checks = [];

    try {
        DB::select('SELECT 1');
        $checks['database'] = 'ok';
    } catch (Throwable $e) {
        $checks['database'] = 'down';
    }

    try {
        Redis::ping();
        $checks['redis'] = 'ok';
    } catch (Throwable $e) {
        $checks['redis'] = 'down';
    }

    $healthy = ! in_array('down', $checks, true);

    return response()->json([
        'status' => $healthy ? 'ok' : 'degraded',
        'checks' => $checks,
    ], $healthy ? 200 : 503);
});

Route::prefix('v1')->name('v1.')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('companies.departments', DepartmentController::class)->scoped();
    Route::apiResource('companies.jobtitles', JobTitleController::class)->scoped();
    Route::apiResource('companies.employees', EmployeeController::class)->scoped();
});
