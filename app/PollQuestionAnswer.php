<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Auth;

class PollQuestionAnswer extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'poll_question_answer_id';
}