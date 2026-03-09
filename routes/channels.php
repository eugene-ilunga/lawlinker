<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('client-chat.{id}', function ($user,$id) {
    return $user->id == $id;
},['guards'=>['web','api']]);
Broadcast::channel('lawyer-chat.{id}', function ($user,$id) {
    return $user->id == $id;
},['guards'=>['lawyer','lawyer_api']]);

Broadcast::channel('online', function($user) {
    return $user->only('id');
}, ['guards'=>['web','api','lawyer','lawyer_api']]);
