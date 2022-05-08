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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/index','MainController@login')->name('login');

Route::get('/addDetails','Receptionist@addRec')->name('addRec');
Route::get('/refNoExist','Receptionist@refNoExist')->name('refNoExist');
Route::get('/newToken','Receptionist@newToken')->name('newToken');
Route::get('/saveDetails','Receptionist@saveDetails')->name('saveDetails');
Route::get('/loadDetails','Receptionist@loadDetails')->name('loadDetails');
Route::post('/dashboard','Receptionist@submitDetails')->name('submitDetails');
Route::get('/actionSubmit','Receptionist@actionSubmit')->name('actionSubmit');

Route::get('/loadData','MainController@loadData')->name('loadData');
Route::get('/loadEmpDetails','MainController@loadEmpDetails')->name('loadEmpDetails');


Route::get('/history','MainController@history')->name('history');