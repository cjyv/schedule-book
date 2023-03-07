<?php

use Illuminate\Support\Facades\Route;

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



Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/schedule-add', [App\Http\Controllers\ScheduleController::class, 'scheduleAdd'])->name('schedule-add');
Route::post('/schedule-get', [App\Http\Controllers\ScheduleController::class, 'scheduleGet'])->name('schedule-get');
Route::post('/schedule-delete',[App\Http\Controllers\ScheduleController::class,'scheduleDelete'])->name('schedule-delete');
Route::post('/schedule-edit',[App\Http\Controllers\ScheduleController::class, 'scheduleEdit'])->name('schedule-edit');