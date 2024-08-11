<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/item', [ItemController::class, 'Item']);
Route::post('create-item',[ItemController::class,'createItem']);
Route::post('update-item',[ItemController::class,'updateItem']);
Route::post('delete-item',[ItemController::class,'deleteItem']);

