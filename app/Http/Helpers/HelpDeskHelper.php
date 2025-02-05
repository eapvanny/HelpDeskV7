<?php

use Illuminate\Support\Facades\Auth;

function getAuthUser()
{
    $auth = Auth::user();

    return $auth;
}