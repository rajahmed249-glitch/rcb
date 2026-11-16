<?php

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
Route::get('/records', [PageController::class, 'records'])->name('pages.records');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');

Route::get('/search', SearchController::class)->name('search');
