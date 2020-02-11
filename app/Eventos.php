<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    protected $table = 'eventos';
    protected $guarded = ['id_aut_evento', 'image_path'];
    protected $primaryKey = ['id_aut_evento'];
}
