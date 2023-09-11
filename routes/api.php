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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/athenticated', function () {
    return true;
});
Route::post('register', 'RegisterController@register');
Route::post('login', 'LoginController@login');
Route::post('logout', 'LoginController@logout');
Route::get('section', 'SectionController@index');
Route::get('alliance', 'api\AllianceController@index');
Route::get('alliance/{rut}/edit', 'api\AllianceController@edit');
Route::post('alliance/update/{rut}', 'api\AllianceController@update');
Route::get('alliance/activate/{rut}', 'api\AllianceController@activate');
Route::post('alliance/store', 'api\AllianceController@store');
Route::get('alliance/list', 'api\AllianceController@list');
Route::get('alliance/destroy/{id}', 'api\AllianceController@destroy');
Route::get('section', 'api\SectionController@index');
Route::get('section/{rut}/edit', 'api\SectionController@edit');
Route::get('section/list', 'api\SectionController@list');
Route::post('section/update/{rut}', 'api\SectionController@update');
Route::get('section/destroy/{id}', 'api\SectionController@destroy');
Route::post('section/store', 'api\SectionController@store');
Route::get('section/activate/{id}', 'api\SectionController@activate');
Route::get('category', 'api\CategoryController@index');
Route::get('category/{id}/edit', 'api\CategoryController@edit');
Route::post('category/store', 'api\CategoryController@store');
Route::get('category/destroy/{id}', 'api\CategoryController@destroy');
Route::post('category/show', 'api\CategoryController@show');
Route::get('category/list', 'api\CategoryController@list');
Route::get('category/list/{id}', 'api\CategoryController@list');
Route::post('category/update/{id}', 'api\CategoryController@update');
Route::get('content', 'api\ContentController@index');
Route::get('content/:id/edit', 'api\ContentController@edit');
Route::post('content/store', 'api\ContentController@store');
Route::get('user', 'api\UserController@index');
Route::post('user/store', 'api\UserController@store');
Route::get('user/{rut}/edit', 'api\UserController@edit');
Route::post('user/update/{rut}', 'api\UserController@update');
Route::get('user/destroy/{id}', 'api\UserController@destroy');
Route::get('user/activate/{rut}', 'api\UserController@activate');
Route::get('user/rol', 'api\UserController@rol');
Route::get('user/rut', 'api\UserController@rut');
Route::post('user/catch', 'api\UserController@catch');
Route::post('modal/catch', 'api\ModalController@catch');
Route::post('section/home', 'api\SectionController@home');
Route::get('poll', 'api\PollController@index');
Route::get('poll/{rut}/edit', 'api\PollController@edit');
Route::post('poll/update/{rut}', 'api\PollController@update');
Route::get('poll/activate/{rut}', 'api\PollController@activate');
Route::post('poll/store', 'api\PollController@store');
Route::get('poll/list', 'api\PollController@list');
Route::get('poll/destroy/{id}', 'api\PollController@destroy');
Route::post('poll/question/{id}', 'api\PollController@question');
Route::post('content/show', 'api\ContentController@show');
Route::get('content/detail/{id}', 'api\ContentController@detail');
Route::get('content/{id}/edit', 'api\ContentController@edit');
Route::post('content/update/{id}', 'api\ContentController@update');
Route::get('content/destroy/{id}', 'api\ContentController@destroy');
Route::get('content/accept/{id}', 'api\ContentController@accept');
Route::get('content/image/{id}', 'api\ContentController@image');
Route::post('audit/store', 'api\AuditController@store');
Route::get('poll/show/{id}', 'api\PollController@show');
Route::get('poll/detail/{id}', 'api\PollController@detail');
Route::get('poll/all/{id}', 'api\PollController@all');
Route::get('poll/quantity/{id}', 'api\PollController@quantity');
Route::post('poll/answer', 'api\PollController@answer');
Route::post('poll/specialanswer', 'api\PollController@specialanswer');
Route::post('poll/update/{id}', 'api\PollController@update');
Route::get('audit', 'api\AuditController@index');
Route::get('section/move/{id}/{position}', 'api\SectionController@move');
Route::get('category/move/{id}/{position}/{section_id}', 'api\CategoryController@move');
Route::get('metric', 'api\MetricController@index');
Route::post('content/date', 'api\ContentController@date');
Route::get('content/stop/{id}', 'api\ContentController@stop');
Route::get('content/play/{id}', 'api\ContentController@play');
Route::get('content/list', 'api\ContentController@list');
Route::get('content/list/{id}', 'api\ContentController@list');
Route::post('content/search/{section_id}/{category_id}', 'api\ContentController@index');
Route::post('category/search/{section_id}', 'api\CategoryController@index');
Route::post('section/search/{title}', 'api\SectionController@index');
Route::post('alliance/search/{rut}/{name}', 'api\AllianceController@index');
Route::get('section/poll/{id}', 'api\SectionController@poll');
Route::get('category/poll/{id}', 'api\CategoryController@poll');
Route::get('content/poll/{id}', 'api\ContentController@poll');
Route::get('content/move/{id}/{position}/{section_id}', 'api\ContentController@move');
Route::get('metric/city', 'api\MetricController@city');
Route::get('metric/region', 'api\MetricController@region');
Route::get('metric/page', 'api\MetricController@page');
Route::get('metric/top_visit', 'api\MetricController@top_visit');
Route::get('poll/result/{id}', 'api\PollController@result');
Route::get('poll/excel/{id}', 'api\PollController@excel');
Route::get('scraping/search', 'api\ScrapingController@search');
Route::get('section_content/show/{id}', 'api\SectionContentController@show');
Route::get('special_section/show/{id}', 'api\SpecialSectionController@show');
Route::get('information', 'api\InformationController@index');
Route::get('information/show/{id}', 'api\InformationController@show');
Route::get('region', 'api\RegionController@index');
Route::get('commune/{region_id}', 'api\CommuneController@index');
Route::post('region/find', 'api\RegionController@find');
Route::post('commune/find', 'api\CommuneController@find');
Route::get('section_region/{id}/edit', 'api\SectionRegionController@edit');
Route::get('section_commune/{id}/edit', 'api\SectionCommuneController@edit');
Route::get('category_region/{id}/edit', 'api\CategoryRegionController@edit');
Route::get('category_commune/{id}/edit', 'api\CategoryCommuneController@edit');
Route::get('rol', 'api\RolController@index');
Route::post('rol/store', 'api\RolController@store');
Route::get('permission', 'api\PermissionController@index');
Route::get('rol/{id}/edit', 'api\RolController@edit');
Route::post('rol/update/{id}', 'api\RolController@update');
Route::get('rol/destroy/{id}', 'api\RolController@destroy');
Route::get('rol_permission/{id}/edit', 'api\RolPermissionController@edit');
Route::get('content_region/{id}/edit', 'api\ContentRegionController@edit');
Route::get('content_commune/{id}/edit', 'api\ContentCommuneController@edit');