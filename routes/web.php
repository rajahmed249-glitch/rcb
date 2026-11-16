<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MatchController as AdminMatchController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlayerApplicationController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SponsorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/be-a-player', [PlayerApplicationController::class, 'create'])->name('be-a-player.create');
Route::post('/be-a-player', [PlayerApplicationController::class, 'store'])->name('be-a-player.store');

Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches/{match}', [MatchController::class, 'show'])->name('matches.show');

Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
Route::get('/players/{player}', [PlayerController::class, 'show'])->name('players.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{post}', [NewsController::class, 'show'])->name('news.show');

Route::get('/sponsors', [SponsorController::class, 'index'])->name('sponsors.index');

Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/gallery', [PageController::class, 'gallery'])->name('pages.gallery');
Route::get('/teams', [PageController::class, 'teams'])->name('pages.teams');
Route::get('/teams/{slug}', [PageController::class, 'teamProfile'])->name('pages.team-profile');
Route::get('/records', [PageController::class, 'records'])->name('pages.records');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');

Route::get('/search/live', [SearchController::class, 'live'])->name('search.live');
Route::get('/search', SearchController::class)->name('search');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('players', AdminPlayerController::class);
        Route::resource('matches', AdminMatchController::class)->except(['show']);
        Route::resource('news', AdminNewsController::class)->except(['show']);
        Route::resource('sponsors', AdminSponsorController::class)->except(['show']);

        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    });
});
