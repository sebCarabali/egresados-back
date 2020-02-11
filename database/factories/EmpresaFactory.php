<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Empresa::class, function (Faker $faker) {
    $datos = [
        "nit" => $faker->unique()->numberBetween(10000000, 999999999999999),
        "nombre" => $faker->unique()->company,
        "anio_creacion" => $faker->numberBetween(1900, 2019),
        "numero_empleados" => "11 - 50",
        "ingresos" => "0 - 3.000.000",
        "sitio_web"=> $faker->unique()->url,
        "id_direccion" => 1,
        "estado" => "Pendiente",
        "fecha_registro" => Carbon::now("-5:00")->format("Y-m-d"),
        "fecha_activacion" => Carbon::now("-5:00")->format("Y-m-d"),
        "fecha_vencimiento" => Carbon::now("-5:00")->format("Y-m-d"),
        "total_publicaciones" => 0,
        "limite_publicaciones" => 0,
        "num_publicaciones_actuales" => 0,
        "correo" => $faker->unique()->companyEmail,
        "telefono" => $faker->numberBetween(10000000, 9999999999),
        "url_logo" => "url logo",
        "url_doc_camaracomercio" => "url camara comercio",
        "descripcion" => $faker->sentence(30)        
    ];
    return array_merge($datos,['razon_social' => $datos["nombre"]." S.A"]);
});
