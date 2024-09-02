<?php

use App\Support\PostFixtures;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/post-content', function () {
        return PostFixtures::getFixtures()->random();
    });
});

Route::middleware('web')->group(function () {});
