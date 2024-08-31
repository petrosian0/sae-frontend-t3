<?php

use App\Http\Controllers\RegistrationController;

Route::get('/registration', [RegistrationController::class, 'showRegistrationForm'])->name('registration.form');
Route::post('/registration', [RegistrationController::class, 'store'])->name('registration.submit');
