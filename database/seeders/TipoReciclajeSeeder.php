<?php
// database/seeders/TipoReciclajeSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoReciclaje;

class TipoReciclajeSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            'Plástico',
            'Papel y Cartón',
            'Vidrio',
            'Metal',
            'Orgánico',
            'Electrónicos'
        ];

        foreach ($tipos as $tipo) {
            TipoReciclaje::create(['nombre' => $tipo]);
        }
    }
}