<?php

use Illuminate\Support\Facades\Route;
use Spork\Greenhouse\Http\Controllers\PlantsController;

Route::get('{abstract_model}', [PlantsController::class, 'index']);
Route::post('{abstract_model}', [PlantsController::class, 'store']);
Route::get('{abstract_model}/{abstract_model_id}', [PlantsController::class, 'show']);
// Updating
Route::put('{abstract_model}/{abstract_model_id}', [PlantsController::class, 'update']);
Route::patch('{abstract_model}/{abstract_model_id}', [PlantsController::class, 'update']);
// Restoring
Route::post('{abstract_model}/{abstract_model_id}/restore', [PlantsController::class, 'restore']);
// Soft-deleting
Route::delete('{abstract_model}/{abstract_model_id}', [PlantsController::class, 'destroy']);
// Force delete
Route::delete('{abstract_model}/{abstract_model_id}/force', [PlantsController::class, 'forceDestroy']);
