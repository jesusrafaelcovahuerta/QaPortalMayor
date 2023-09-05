<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Auth;

class CategoryCommune extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'category_commune_id';
}
