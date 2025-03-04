<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\FieldController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('subscribers', SubscriberController::class);
Route::apiResource('fields', FieldController::class);
