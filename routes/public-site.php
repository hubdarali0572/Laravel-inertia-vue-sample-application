<?php

use App\Http\Controllers\PublicSite\PublicSitePageController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicSitePageController::class)->group(function () {
    Route::get('/', 'home')->name('publicSite.home');
    Route::get('/about', 'about')->name('publicSite.about');
});
