<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

abstract class NotifiableModel extends Model
{
    use Notifiable;
}
