<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

/**
 * Description of EgresadoBuilder
 *
 * @author sebastian
 */
class EgresadoBuilder {
    
    private $egresado;
    
    public function __construct($id = null) {
        $this->egresado = new \App\Egresado();
        if($id) {
            $this->egresado = \App\Egresado::where('id_aut_egresado', $id)->first();
        }
    }
    
    public function setLugarResidencia(\App\Localizacion $lugarResidencia) {
        $this->egresado->lugarResidencia()->associate($lugarResidencia);
        return $this;
    }
    
    public function setLugarNacimiento(\App\Localizacion $lugarNacimiento) {
        $this->egresado->ciudadNacimiento()->associate($lugarNacimiento);
        return $this;
    }
    
    public function build() {
        return $this->egresado;
    }
    
}
