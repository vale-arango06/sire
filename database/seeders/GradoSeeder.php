<?php
// database/seeders/GradoSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grado;

class GradoSeeder extends Seeder
{
    public function run()
    {
        $grados = [
            'Sexto',
            'Séptimo',
            'Octavo',
            'Noveno',
            'Décimo',
            'Once'
        ];

        foreach ($grados as $grado) {
            Grado::create(['nombre' => $grado]);
        }
    }
}