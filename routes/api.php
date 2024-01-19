<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});

Route::namespace('App\Http\Controllers\API')->group(function () {
    //GET
    Route::get('profile', 'ProfileController@profile');
    Route::get('category/list', 'CategoryController@list');
    Route::get('user/list', 'UserController@list');
    Route::get('inventory', 'InventoryController@index');
    Route::get('inventory/search', 'InventoryController@search');
    Route::get('employee/list', 'EmployeeController@list');
    Route::get('inventory/all', 'InventoryController@all');
    Route::get('dashboard/count', 'InventoryController@counts');
    Route::get('dashboard/instorage', 'InventoryController@inStorage');
    //POST
    Route::post('change-password', 'ProfileController@changePassword');
    Route::post('inventory/duplicate/{id}', 'InventoryController@duplicate');
    //PUT
    Route::put('profile', 'ProfileController@updateProfile');
    //RESOURCES
    Route::apiResources([
        'user' => 'UserController',
        'inventory' => 'InventoryController',
        'category' => 'CategoryController',
        'employee' => 'EmployeeController'
    ]);
});
