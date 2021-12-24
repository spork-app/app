<?php

Route::group([ 'prefix' => 'api' ], function () {
    Route::post('/',function () {
        $data = request()->all();

        dispatch_now(new \App\Features\Contacts\Jobs\SyncContactsJob($data));

        return [
            'message' => 'Contacts synced',
        ];
    });
});
Route::get('/', App\Features\Reminders\Http\Controllers\ApiController::class.'@index');
