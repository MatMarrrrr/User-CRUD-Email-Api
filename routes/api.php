<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmailAddressController;

Route::apiResource('users', UserController::class);
Route::apiResource('users.emails', EmailAddressController::class);
