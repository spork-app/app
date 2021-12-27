<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(RedirectIfAuthenticated::class)->get('/login', fn() => view('welcome'));

Route::get('/{uri?}/{subPath?}', fn() => view('welcome'))
    ->middleware('auth:sanctum')
    ->where('uri', '^((?!(api)).)*')
    ->where('subPath', '.*');
