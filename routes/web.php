<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


