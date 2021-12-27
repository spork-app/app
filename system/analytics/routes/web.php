<?php
Route::middleware('auth:sanctum')->post('event', \Spork\Analytics\Http\Controllers\EventController::class);
