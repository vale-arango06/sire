<?php
// database/seeders/GrupoSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grupo;
use App\Models\Grado;

class GrupoSeeder extends Seeder
{
    public function run()
    {
        $grados = Grado::all();
        
        foreach ($grados as $grado) {
            Grupo::create([
                'grado_id' => $grado->id,
                'nombre' => $grado->nombre . ' A'
            ]);
            Grupo::create([
                'grado_id' => $grado->id,
                'nombre' => $grado->nombre . ' B'
            ]);
        }
    }
}