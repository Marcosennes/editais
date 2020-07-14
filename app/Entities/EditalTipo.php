<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\EditalTipo as Authenticatable;
use Illuminate\Notifications\Notifiable;

class EditalTipo extends Authenticatable
{
    use Notifiable;

    protected $table = 'edital_tipos';
    protected $fillable = [
        'id',
        'nome',
    ];

    protected $hidden = [
        
    ];

    public function editals(){

        return $this->hasMany(Edital::class);

    }
}
