<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'evento';
    protected $guarded = ['id_aut_evento'];
    protected  $primaryKey = 'id_aut_evento';
    public $timestamps = false;
}
