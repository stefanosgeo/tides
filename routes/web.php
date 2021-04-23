<?php

use App\Http\Controllers\Backend\AssetsController;
use App\Http\Controllers\Backend\ClipsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DropzoneTransferController;
use App\Http\Controllers\Backend\SeriesClipsController;
use App\Http\Controllers\Backend\SeriesController;
use App\Http\Controllers\Frontend\ApiTagsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ShowClipsController;
use App\Http\Controllers\Frontend\ShowSeriesController;
use Illuminate\Support\Facades\App;
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

Route::get('/', HomeController::class)->name('home');
Route::redirect('/home', '/');
Route::redirect('/admin', '/admin/dashboard');

//Quick search
Route::get('search', [SearchController::class, 'search'])->name('search');

//Frontend clip route
Route::get('/clips', [ShowClipsController::class, 'index']);
Route::get('/clips/{clip}', [ShowClipsController::class, 'show']);

Route::get('/series/{series}', [ShowSeriesController::class, 'show']);

Route::get('/api/tags', ApiTagsController::class)->name('api.tags');

//Backend routes
Route::prefix('admin')->middleware('auth')->group(function () {
    //Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    //Series
    Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
    Route::get('/series/create', [SeriesController::class, 'create'])->name('series.create');
    Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
    Route::get('/series/{series}', [SeriesController::class, 'edit'])->name('series.edit');
    Route::patch('/series/{series}', [SeriesController::class, 'update'])->name('series.update');
    Route::delete('series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');

    Route::get('/series/{series}/addClip',[SeriesClipsController::class, 'create'])->name('seriesClips.create');

    //Clip
    Route::get('/clips', [ClipsController::class, 'index'])->name('clips.index');
    Route::get('/clips/create', [ClipsController::class, 'create'])->name('clips.create');
    Route::post('/clips', [ClipsController::class, 'store'])->name('clips.store');
    Route::get('/clips/{clip}/', [ClipsController::class, 'edit'])->name('clips.edit');
    Route::patch('/clips/{clip}/', [ClipsController::class, 'update'])->name('clips.update');
    Route::delete('/clips/{clip}/', [ClipsController::class, 'destroy'])->name('clips.destroy');

    Route::get('/clips/{clip}/transfer', [DropzoneTransferController::class, 'listFiles'])
        ->name('admin.clips.dropzone.listFiles');
    Route::post('/clips/{clip}/transfer', [DropzoneTransferController::class, 'transfer'])
        ->name('admin.clips.dropzone.transfer');


    //Assets
    Route::post('/clips/{clip}/assets', [AssetsController::class, 'store'])->name('admin.assets.store');
    Route::delete('assets/{asset}', [AssetsController::class, 'destroy'])->name('assets.destroy');
});

//change portal language
Route::get('/set_lang/{locale}', function($locale){

    if(! in_array($locale, ['en','de'])){
        abort(400);
    }

    session()->put('locale', $locale);

    return back();

});
Auth::routes();

