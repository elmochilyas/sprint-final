<?php

use App\Http\Controllers\Api\BlueprintController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'ThreadForge API',
    ]);
});

Route::get('/blueprints',[BlueprintController::class, 'index']);
Route::get('/blueprints/{blueprint}', [BlueprintController::class,'show']);