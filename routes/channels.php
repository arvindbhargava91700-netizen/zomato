<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if a user can listen to the channel.
|
*/

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    return true;
});
