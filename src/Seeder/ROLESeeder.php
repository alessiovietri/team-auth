<?php

use Illuminate\Database\Seeder;

use MODELSDIR\ROLE as ROLE;

class ROLESeeder extends Seeder
{
    public function run()
    {
        ROLE::where('id', '<>', null)->delete();

        ROLE::create([
            'name' => 'John Smith',
            'email' => 'john.smith@email.com',
            'password' => bcrypt('password'),
        ]);
    }
}
