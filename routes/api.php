<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController',
    'homes' =>'API\HomeController',
    'sliders' => 'API\SliderController',
    'services' => 'API\ServicesController',
    'abouts' => 'API\AboutController',
    'teams' => 'API\TeamController',
    'articles' => 'API\ArticlesController',
    'categories' => 'API\CategoryController',
    'teamleader' => 'API\TeamLeaderController',
    'galleries' => 'API\GalleryController',
    'partners' => 'API\PartnerController',
    'clients' => 'API\ClientsController',
    'tags' => 'API\TagsController',
    'topics' => 'API\TopicController'

]);
Route::get('profile', 'API\UserController@profile');
Route::put('profile','API\UserController@updateProfile');
