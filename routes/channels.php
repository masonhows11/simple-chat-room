<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('chat_channel', static function (\App\Models\User $user) {
    //  return false; false means the user login can not join to channel
    // return true;  true means the user login can  join to channel

    return [
        'display_name' => $user->name,
        'email' => $user->email,
        'uuid' => str($user->email)->replace('@dick.chat',''),
        'avatar' => $user->avatar == null ? asset('images/profile_user.png') : '',
    ];
});
