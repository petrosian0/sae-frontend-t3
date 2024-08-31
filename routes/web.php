<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventAdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;

// Default welcome page
Route::get('/', function () {
    return view('welcome');
});

// Protecting routes with 'auth' middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/calendar', [EventController::class, 'index'])->name('calendar');

    // Event Routes for FullCalendar
    Route::get('/events', [EventController::class, 'fetchEvents']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
   //

    // Event Admin Routes
    Route::get('/manage-events', [EventAdminController::class, 'index'])->name('events.admin.index');
    Route::get('/manage-events/data', [EventAdminController::class, 'fetchEventsData'])->name('events.admin.fetch');
    Route::get('/manage-events/{id}', [EventAdminController::class, 'show'])->name('events.admin.show');
    Route::post('/manage-events', [EventAdminController::class, 'store'])->name('events.admin.store');
    Route::put('/manage-events/{id}', [EventAdminController::class, 'update'])->name('events.admin.update');
    Route::delete('/manage-events/{id}', [EventAdminController::class, 'destroy'])->name('events.admin.destroy');
    // Roles Routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Users Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Route to fetch all users as JSON
    Route::get('/users/data', [UserController::class, 'fetchUsersData'])->name('users.fetch');

    // Other CRUD routes for users
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/data', [RoleController::class, 'fetchRolesData'])->name('roles.fetch');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');


});

// Registration Routes
Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
