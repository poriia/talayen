<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\API\TransactionController;

Route::prefix('/v1')->group(function () {
    Route::post('/login', function (Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', function (Request $request) {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['success' => 'logout successfully'], 200);
        });

        Route::prefix('/orders')->group(function () {
            Route::post('/buy', [OrderController::class, 'buy']);
            Route::post('/sell', [OrderController::class, 'sell']);
            Route::post('cancel/{id}', [OrderController::class, 'cancel']);
        });
        Route::get('transactions', [TransactionController::class, 'index']);
    });
});
