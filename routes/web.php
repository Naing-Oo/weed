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
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::resource('agents', AgentController::class);
    Route::get('/agents/get-commission-rate/{roleName}', 'AgentController@getCommissionRate');

    Route::resource('subAgents', SubAgentController::class);
    Route::get('/subAgents/get-commission-rate/{roleName}', 'SubAgentController@getCommissionRate');

    Route::resource('influencers', InfluencerController::class);
    Route::get('/influencers/get-commission-rate/{roleName}', 'InfluencerController@getCommissionRate');
    Route::get('/influencers/get-sub-agent-by-agent/{id}', 'InfluencerController@getSubAgentByAgentId');
    // Route::get('/influencers/get-agent-by-sub-agentId/{id}', 'InfluencerController@getAgentBySubAgentId');
    

    Route::resource('linkTransactions', GenerateLinkController::class);
    Route::get('/linkTransactions/get-link-by-product-id/{id}', 'GenerateLinkController@getLinkById');

    Route::resource('sales-transaction', TransactionController::class);
    Route::get('transaction/reportSelection', 'TransactionController@reportSelection');
    Route::post('transaction/displayReport', 'TransactionController@displayReport')->name('transaction.displayReport');
    Route::post('transaction/exportExcel', 'ExportExcelController@exportExcel')->name('transaction.exportExcel');


});

Route::get('/linkTransactions/commission/{id}', 'GenerateLinkController@commission');




