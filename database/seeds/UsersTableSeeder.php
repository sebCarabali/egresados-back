<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ofertas.users')->insert([
            'id' => '1',
            'email' => 'juan@empresa.com',
            'password' => hash('sha256', '12345'),
            'id_rol' => '1',
            'first_name' => 'Camilo',
            'last_name' => 'Forero'
        ]);
    }
}
