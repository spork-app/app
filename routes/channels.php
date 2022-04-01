<?php

use Illuminate\Support\Facades\Broadcast;
use Spork\Development\Events\PublishGitInformationRequested;

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});