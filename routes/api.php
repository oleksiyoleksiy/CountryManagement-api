<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Building\BuildingController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\CountryBuilding\CountryBuildingController;
use App\Http\Controllers\Image\ImageController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'ability:' . TokenAbility::ACCESS_API->value])->group(function () {
    Route::get('/user/current', fn (Request $request) => $request->user());
    Route::resource('building', BuildingController::class);
    Route::resource('country', CountryController::class);
    Route::resource('country.building', CountryBuildingController::class);
    Route::post('/country/{country}/building/income', [CountryBuildingController::class, 'collectIncome']);
});

Route::get('/image/{image}', [ImageController::class, 'show']);

Route::resource('user', UserController::class);


Route::controller(AccountController::class)->group(function () {
    Route::post('/login', 'login')->middleware('guest:sanctum');
    Route::post('/register', 'register')->middleware('guest:sanctum');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/refresh', 'refresh')->middleware('auth:sanctum', 'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value);
});
