<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Sell_Your_CarController;
use App\Http\Controllers\Trade_Your_CarController;



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

Route::post('/register', [UserController::class, 'register']);
Route::post('/forget', [UserController::class, 'forgetPass']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/check_vin', [UserController::class, 'checkVin']);
Route::post('/contact_us', [UserController::class, 'contact']);

Route::group([
    'middleware' => 'jwt.verify'
], function () {
    Route::group([
        'middleware' => 'owner'
    ], function () {
        Route::post('/sell_your_car', [Sell_Your_CarController::class, 'add']);
        Route::post('/trade_your_car', [Trade_Your_CarController::class, 'add']);
    });

    Route::post('/resetpassword', [UserController::class, 'resetPassword']);
    Route::post('/update_user', [UserController::class, 'update']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/trade_your_car_list', [Trade_Your_CarController::class, 'list']);
    Route::get('/sell_your_car_list', [Trade_Your_CarController::class, 'listSell']);
    Route::get('/list_auction_owner', [Trade_Your_CarController::class, 'list_owner']);
    Route::get('/list_auction_dealer', [Trade_Your_CarController::class, 'list_dealer']);
    Route::get('/list_auction_dealer_top', [Trade_Your_CarController::class, 'list_dealer_top']);
    Route::get('/get_active_states', [Trade_Your_CarController::class, 'get_active_states']);
    //Route::get('/sell_your_car_list', [Trade_Your_CarController::class, 'listSell'])->middleware('auth');
    Route::get('/get_all', [Trade_Your_CarController::class, 'GetAll']);
    Route::get('applied_auction/{section?}/{filter?}', [Trade_Your_CarController::class, 'section']);
    Route::get('/won/{filter?}', [Trade_Your_CarController::class, 'won']);
    Route::get('/lost/{filter?}', [Trade_Your_CarController::class, 'lost']);
    Route::get('/bidsell/{user_id?}', [Trade_Your_CarController::class, 'bidSell']);
    Route::get('/bidtrade/{user_id?}', [Trade_Your_CarController::class, 'bidTrade']);
    Route::post('/bidstatus', [Trade_Your_CarController::class, 'BidStatus']);

    Route::group([
        'middleware' => 'dealer'
    ], function () {
        Route::post('/addbid', [Trade_Your_CarController::class, 'addBid']);
    });

    Route::post('/car_list', [Trade_Your_CarController::class, 'listAll']);
    Route::get('/messaging_conversation', [Trade_Your_CarController::class, 'messaging_conversation']);
    Route::get('/conversation/{conversation?}', [Trade_Your_CarController::class, 'conversation']);
    Route::post('/addmessaging', [Trade_Your_CarController::class, 'addMessaging']);


    Route::get('/bidhistory/{auction_id?}', [Trade_Your_CarController::class, 'bidHistory']);
});
