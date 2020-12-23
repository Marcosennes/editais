<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = true;
    protected $table = 'users';
    protected $fillable = [
        'cpf',
        'name',
        'email',
        'permission',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

}
