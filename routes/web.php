<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
bertnotes:
-use regular expression constraints for safety

*/

Route::get('/', function () {
    return view('welcome');
});

//$id is required
Route::get('/test/{id}', function ($id) {
    return view('test');
});

Route::get('/teststring/{id}/comment/{comment}', function ($id, $comment) {
    return 'User ' . $id . '  ' . $comment;
});

//works id is required
Route::get('testicles/{id}', 'ApiController@testcontroller');

Route::get('test1', function () {
    return view('test1');
});

Route::get('privacypolicy', function () {
    return view('privacypolicy');
});

Route::get('privacypolicyadwatcher', function () {
    return view('privacypolicyadwatcher');
});
