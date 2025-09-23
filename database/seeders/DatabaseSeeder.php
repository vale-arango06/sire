<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            GradoSeeder::class,
            GrupoSeeder::class,
            TipoReciclajeSeeder::class,
            MaterialSeeder::class,
        ]);
    }
}