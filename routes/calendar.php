<?php

use Illuminate\Support\Facades\Route;

Route::get('/calendar', function () {
    return view('calendar');
});
