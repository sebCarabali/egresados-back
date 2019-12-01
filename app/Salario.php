<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    protected $table = 'salarios';
    protected $primaryKey = 'id_aut_salario';
    public $timestamps = false;


    protected $fillable = [
        "minimo", "maximo", "forma_pago"
    ];

    protected $appends = ['rango'];

    public function oferta()
    {
        return $this->hasOne('App\Oferta', 'id_forma_pago', 'id_aut_salario');
    }

    /**
     * Obtiene el rango de salarios.
     *
     * @return string
     */
    public function getRangoAttribute()
    {
        return "Entre $this->minimo  y $this->maximo";
    }

    public function getFormaPagoAttribute($value)
    {
        return $value;
    }

    public function getMinimoAttribute($value)
    {

        if($this->forma_pago == "US Dolar"){
            setlocale(LC_MONETARY, 'en_US');
            return money_format('%i', $value).'\n';
        }else if($this->forma_pago == "Moneda local"){
            setlocale(LC_MONETARY, 'es_CO');
            // return '$'.$value;
            return money_format('%i', $value).'\n';
        }
    }
    public function getMaximoAttribute($value)
    {
        if($this->forma_pago == "US Dolar"){
            setlocale(LC_MONETARY, 'en_US');
            return money_format('%i', $value).'\n';
            //return $value. ' US$';
        }else if($this->forma_pago == "Moneda local"){
          setlocale(LC_MONETARY, 'es_CO');
          // return '$'.$value;
          return money_format('%n', $value).'\n';
        }
    }
}
