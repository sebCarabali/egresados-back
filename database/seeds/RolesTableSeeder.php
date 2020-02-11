<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ofertas.roles')->insert([
            'id' => '0',
            'nombre' => 'Administrador'
        ]);

        DB::table('ofertas.roles')->insert([
            'id' => '1',
            'nombre' => 'Empresa'
        ]);
        
        DB::table('ofertas.roles')->insert([
            'id' => '2',
            'nombre' => 'Egresado'
        ]);
    }
}
