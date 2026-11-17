<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoleIconController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MatchController as AdminMatchController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\Admin\SquadController;
use App\Http\Controllers\Admin\ScoreboardController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
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
Route::get('/gallery/videos', [PageController::class, 'galleryVideos'])->name('pages.gallery-videos');
Route::get('/records', [PageController::class, 'records'])->name('pages.records');
Route::get('/teams', [PageController::class, 'teams'])->name('pages.teams');
Route::get('/teams/{slug}', [PageController::class, 'teamProfile'])->name('pages.team-profile');
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
        Route::resource('teams', AdminTeamController::class)->except(['show']);
        Route::resource('tournaments', TournamentController::class)->except(['show']);
        Route::resource('locations', LocationController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('faqs', FaqController::class);
        Route::resource('role-icons', RoleIconController::class)->names('role_icons');
        Route::resource('sliders', SliderController::class);

        Route::get('squads', [SquadController::class, 'index'])->name('squads.index');
        Route::get('squads/{match}/edit', [SquadController::class, 'edit'])->name('squads.edit');
        Route::put('squads/{match}', [SquadController::class, 'update'])->name('squads.update');
        Route::delete('squads/{match}', [SquadController::class, 'destroy'])->name('squads.destroy');

        Route::get('scoreboards', [ScoreboardController::class, 'index'])->name('scoreboards.index');
        Route::get('scoreboards/{match}/edit', [ScoreboardController::class, 'edit'])->name('scoreboards.edit');
        Route::put('scoreboards/{match}', [ScoreboardController::class, 'update'])->name('scoreboards.update');
        Route::resource('gallery', AdminGalleryController::class)->except(['show']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    });
});
