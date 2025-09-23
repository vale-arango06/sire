<?php
// database/seeders/MaterialSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $materiales = [
            ['nombre' => 'Botellas de Plástico', 'unidad_medida' => 'unidad', 'puntos' => 2],
            ['nombre' => 'Papel Blanco', 'unidad_medida' => 'kg', 'puntos' => 10],
            ['nombre' => 'Cartón', 'unidad_medida' => 'kg', 'puntos' => 8],
            ['nombre' => 'Latas de Aluminio', 'unidad_medida' => 'unidad', 'puntos' => 5],
            ['nombre' => 'Vidrio', 'unidad_medida' => 'kg', 'puntos' => 6],
            ['nombre' => 'Residuos Orgánicos', 'unidad_medida' => 'kg', 'puntos' => 3],
        ];

        foreach ($materiales as $material) {
            Material::create($material);
        }
    }
}