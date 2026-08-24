<?php

use App\Http\Controllers\PublicSite\PublicSitePageController;
use App\Http\Middleware\ForcePublicEnglish;
use Illuminate\Support\Facades\Route;

Route::middleware(ForcePublicEnglish::class)
    ->controller(PublicSitePageController::class)
    ->group(function () {
        Route::get('/', 'home')->name('publicSite.home');
        Route::get('/about', 'about')->name('publicSite.about');
        Route::get('/academics', 'academics')->name('publicSite.academics');
        Route::get('/admissions', 'admissions')->name('publicSite.admissions');
        Route::get('/campus-life', 'campus')->name('publicSite.campus');
        Route::get('/news', 'news')->name('publicSite.news');
        Route::get('/contact', 'contact')->name('publicSite.contact');
        Route::post('/contact', 'contactSubmit')->name('publicSite.contact.submit');
    });
