<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BugController;




// use App\Http\Controllers\DashboardController;

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
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// routes/web.php



// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Login Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::resource('projects', ProjectController::class);

Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

// Route to fetch project details and associated bugs


// Route to fetch bugs for a specific project (for AJAX)
Route::get('/projects/{id}/bugs', [ProjectController::class, 'getBugs']);


Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

Route::post('/projects/{projectId}/bugs', [ProjectController::class, 'storeBug']);


Route::post('/bugs', [BugController::class, 'store'])->name('bugs.store');
Route::get('/projects/{id}/bugs', [BugController::class, 'index'])->name('bugs.index');
Route::patch('/bugs/{id}', [BugController::class, 'update'])->name('bugs.update');

// routes/web.php
Route::get('/projects/{id}/bugs', [BugController::class, 'index'])->name('bugs.index');


Route::patch('/bugs/{id}', [ProjectController::class, 'updateBugStatus']);

Route::get('/projects/{projectId}/bugs', [BugController::class, 'index'])->name('bugs.index');

Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('project.show');

Route::patch('/bugs/{id}', [BugController::class, 'updateStatus'])->name('bugs.updateStatus');

// Route to show project details and bugs
Route::get('/projects/{projectId}/bugs', [BugController::class, 'show'])->name('projects.bugs');

// Route to create a new bug
Route::post('/projects/{projectId}/bugs', [BugController::class, 'store'])->name('bugs.store');



Route::post('/projects/{projectId}/bugs', [ProjectController::class, 'storeBug'])->name('bugs.store');
Route::patch('/bugs/{id}', [ProjectController::class, 'updateBugStatus'])->name('bugs.update');


Route::get('/projects/{projectId}/bugs', [ProjectController::class, 'getBugs'])->name('bugs.list'); // Add this route


Route::post('/projects/{project}/bugs', [BugController::class, 'store']);
Route::get('/projects/{project}/bugs', [BugController::class, 'index']);

// routes/web.php
Route::get('/projects/{project}/bugs', [ProjectController::class, 'showBugCreationPage'])->name('projects.bugs');

Route::post('/projects/{project}/bugs', [BugController::class, 'store'])->name('bugs.store');

Route::post('/projects/{projectId}/bugs', [BugController::class, 'store'])->name('bugs.store');

Route::delete('/bugs/{id}', [BugController::class, 'destroy']);

Route::delete('/bugs/{id}', [BugController::class, 'destroy'])->name('bugs.destroy');


Route::put('/bugs/{id}', [BugController::class, 'update'])->name('bugs.update');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');




require __DIR__.'/auth.php';
