<?php
// app/Console/Commands/ConsultarPuntos.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Grupo;
use App\Models\RegistroReciclaje;

class ConsultarPuntos extends Command
{
    protected $signature = 'puntos:consultar';
    protected $description = 'Muestra ejemplos de consultas de puntos';

    public function handle()
    {
        $this->info('=== CONSULTAS DE PUNTOS ===');
        
        // Puntos por estudiante
        $this->info("\n1. Top 5 Estudiantes por Puntos:");
        $topEstudiantes = User::withSum('registrosReciclaje', 'puntos_ganados')
            ->orderBy('registros_reciclaje_sum_puntos_ganados', 'desc')
            ->limit(5)
            ->get();

        foreach ($topEstudiantes as $estudiante) {
            $this->line("- {$estudiante->nombre}: {$estudiante->registros_reciclaje_sum_puntos_ganados} puntos");
        }

        // Puntos por grupo
        $this->info("\n2. Top 5 Grupos por Puntos:");
        $topGrupos = Grupo::withSum('registrosReciclaje', 'puntos_ganados')
            ->orderBy('registros_reciclaje_sum_puntos_ganados', 'desc')
            ->limit(5)
            ->get();

        foreach ($topGrupos as $grupo) {
            $this->line("- {$grupo->nombre}: {$grupo->registros_reciclaje_sum_puntos_ganados} puntos");
        }

        // Estadísticas por material
        $this->info("\n3. Reciclaje por Material:");
        $porMaterial = RegistroReciclaje::with('material')
            ->selectRaw('material_id, SUM(cantidad_kg) as total_kg, SUM(puntos_ganados) as total_puntos')
            ->groupBy('material_id')
            ->orderBy('total_puntos', 'desc')
            ->get();

        foreach ($porMaterial as $registro) {
            $this->line("- {$registro->material->nombre}: {$registro->total_kg} kg, {$registro->total_puntos} puntos");
        }

        return 0;
    }
}