<?php

use Dashed\Drift\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;

Route::get('__media/{configName}/{manipulations}/{path}', ImagesController::class)
    ->where('path', '.*')
    ->name('__media.manipulate')
    ->middleware('signed');
