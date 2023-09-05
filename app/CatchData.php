<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Auth;

class CatchData extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'catch_data_id';
}
