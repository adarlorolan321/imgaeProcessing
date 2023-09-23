<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OCRController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Resolution\ResolutionController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/', function () {
  return Inertia::render('login');
});


Route::post('/ocr', [OCRController::class,'performOCR']);

Route::post('/convert-to-pdf', [ImageController::class, 'convertToPDF'])->name('convert');

Route::middleware('auth')->group(function () {
    
    Route::get('layout', function () {
        return Inertia::render('layout');
    });
    Route::resource('profile', ProfileController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('resolutions', ResolutionController::class);
    Route::post('resolutions-view', [ResolutionController::class, 'view'])->name('resolutions_view');
    
});

Route::post('print_supplies', [SupplyController::class, 'print'])->name('print_supplies');

Route::get('resolutions-view/{id}', function () {
  $pdf = PDF::loadView('image', ['data' => 'test']);
  return $pdf->stream('test-pdf.pdf');
});
require __DIR__ . '/auth.php';