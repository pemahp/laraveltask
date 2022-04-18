<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvFileUploaderController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });20220416204231.csv


Route::get('/csv-data', [CsvFileUploaderController::class,'getSavedFileData']);
Route::get('/save-file', [CsvFileUploaderController::class,'saveTodb']);
Route::post('/csv-data', [CsvFileUploaderController::class,'getandSaveData']);
// Route::post('register', [RegisterController::class, 'register']);