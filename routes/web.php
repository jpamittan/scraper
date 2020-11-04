<?php

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

use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::post('/parseImport', [HomeController::class, 'parseImport'])->name('home.parseImport');
Route::post('/processImport', [HomeController::class, 'processImport'])->name('home.processImport');
Route::post('/processSnovIo', [HomeController::class, 'processSnovIo'])->name('home.processSnovIo');
Route::get('/results', [HomeController::class, 'results'])->name('home.results');
Route::get('/export', [ExportController::class, 'export'])->name('export.export');
