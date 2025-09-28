<?php

namespace App\Filament\Estudiante\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Recompensa;

class RecompensasWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        $user = Auth::user();

        return $table
            ->query(Recompensa::query())
            ->columns([
                TextColumn::make('nombre')->label('Recompensa'),
                TextColumn::make('descripcion')->label('Descripción')->wrap(),
                TextColumn::make('puntos_requeridos')->label('Puntos requeridos'),
            ])
            ->actions([
                Action::make('reclamar')
                    ->label('Reclamar')
                    ->color('success')
                    ->icon('heroicon-m-gift')
                    ->disabled(fn ($record) => ! $user || ((int) ($user->puntos ?? 0) < (int) $record->puntos_requeridos))
                    ->action(function ($record) use ($user) {
                        if (! $user) {
                            $this->notify('danger', 'Usuario no autenticado.');
                            return;
                        }

                        $required = (int) $record->puntos_requeridos;
                        if ($required <= 0) {
                            $this->notify('danger', 'Puntos requeridos inválidos.');
                            return;
                        }

                        try {
                            // Decrement atómico solo si tiene suficientes puntos
                            $affected = DB::table('users')
                                ->where('id', $user->id)
                                ->where('puntos', '>=', $required)
                                ->decrement('puntos', $required);

                            if ($affected === 0) {
                                $this->notify('danger', 'No tienes suficientes puntos.');
                                return;
                            }

                            // opcional: registrar en pivot/tabla de historial
                            // DB::table('usuario_recompensas')->insert([...]);

                            $this->notify('success', '¡Has reclamado la recompensa!');
                        } catch (\Throwable $e) {
                            $this->notify('danger', 'Error al reclamar: ' . $e->getMessage());
                        }
                    }),
            ]);
    }
}
