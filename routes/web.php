<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpinnerController;
use App\Models\GiftItems;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $segments = GiftItems::wheelSegments();
    $prizesRemaining = GiftItems::where('qty', '>', 0)->sum('qty');

    return view('welcome', compact('segments', 'prizesRemaining'));
});

Route::post('spin',[SpinnerController::class,'shuffle'])->name('spinner.shuffle');
