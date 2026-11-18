<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeospatialController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\RawContentController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\SyncJobController;
use App\Http\Controllers\TopicAgencyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SISMANTIK Web Routes (Custom Luxury Executive UI)
|--------------------------------------------------------------------------
*/

// Executive War-Room Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Standalone Geospatial 21 Kecamatan GIS
Route::get('/peta', [GeospatialController::class, 'index'])->name('peta');

// Raw Content & Public Opinion Catalog
Route::get('/berita', [RawContentController::class, 'index'])->name('berita');

// OPD Directory & Keywords
Route::get('/opd', [TopicAgencyController::class, 'index'])->name('opd');
Route::post('/opd/{id}/toggle', [TopicAgencyController::class, 'toggle'])->name('opd.toggle');

// Media Portals & Sources
Route::get('/sumber', [SourceController::class, 'index'])->name('sumber');

// Sync Audit Trail
Route::get('/sinkronisasi', [SyncJobController::class, 'index'])->name('sinkronisasi');

// Operational Guide & SOP
Route::get('/panduan', [GuideController::class, 'index'])->name('panduan');

// Actions & Incremental / Total Sync
Route::post('/action/sync', [ActionController::class, 'sync'])->name('action.sync');
Route::post('/action/replace-total', [ActionController::class, 'replaceTotal'])->name('action.replace-total');

// API: Article Drilldown Modal
Route::post('/api/drilldown-articles', [ActionController::class, 'drilldownArticles'])->name('api.drilldown-articles');

/*
|--------------------------------------------------------------------------
| Legacy / Admin Aliases (Seamlessly Redirect to Custom UI)
|--------------------------------------------------------------------------
*/
Route::get('/admin/peta-geospasial', fn() => redirect('/peta'));
Route::get('/admin/raw-contents', fn() => redirect('/berita'));
Route::get('/admin/topic-or-agencies', fn() => redirect('/opd'));
Route::get('/admin/sources', fn() => redirect('/sumber'));
Route::get('/admin/sync-jobs', fn() => redirect('/sinkronisasi'));
Route::get('/admin/panduan-sistem', fn() => redirect('/panduan'));
Route::get('/admin', fn() => redirect('/dashboard'));
Route::get('/admin/{any}', fn() => redirect('/dashboard'))->where('any', '.*');
