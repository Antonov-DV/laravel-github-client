<?php

use Illuminate\Http\Request;
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

Route::get('rest/', 'RestController@index');
Route::get('rest/list-repo-commits', 'RestController@listRepoCommits');
Route::options('rest/{action}', 'RestController@options');
Route::post('rest/create-repo', 'RestController@createRepo');
Route::post('rest/import-repo', 'RestController@importRepo');
Route::post('rest/delete-repo-commits', 'RestController@deleteRepoCommits');