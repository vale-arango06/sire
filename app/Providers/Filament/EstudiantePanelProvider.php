<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class EstudiantePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('estudiante')
            ->path('estudiante')
            ->colors([
                'primary' => Color::Fuchsia,
            ])
            ->login()
            ->brandName('SIRE')
            ->favicon(asset('imagenes/logo-sire.png'))
            ->discoverResources(in: app_path('Filament/Estudiante/Resources'), for: 'App\\Filament\\Estudiante\\Resources')
            ->discoverPages(in: app_path('Filament/Estudiante/Pages'), for: 'App\\Filament\\Estudiante\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Estudiante/Widgets'), for: 'App\\Filament\\Estudiante\\Widgets')
            ->widgets([
                \App\Filament\Estudiante\Widgets\EstudianteStats::class,
                \App\Filament\Estudiante\Widgets\RecompensasWidget::class,
            ])
            ->renderHook('panels::body.end', fn() => <<<'HTML'
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Función para aplicar cambios
                        function aplicarCambios() {
                            // Ocultar título Dashboard
                            const dashboardTitle = document.querySelector('.fi-header h1, .fi-header-heading, header h1');
                            if (dashboardTitle) {
                                dashboardTitle.style.display = 'none';
                            }

                            // Buscar y ocultar widgets de Welcome y Filament
                            const widgets = document.querySelectorAll('[class*="fi-wi"], .fi-section');
                            widgets.forEach(widget => {
                                const content = widget.textContent || '';
                                if (content.includes('Welcome') || content.includes('nelson') || 
                                    content.includes('filament') || content.includes('v3.3.0')) {
                                    widget.style.display = 'none';
                                }
                            });

                            // Modificar el logo SIRE
                            const brandElements = document.querySelectorAll('.fi-logo, [class*="brand"]');
                            brandElements.forEach(brand => {
                                if (brand.textContent.includes('SIRE')) {
                                    brand.style.cssText = `
                                        color: #10b981 !important;
                                        font-weight: 900 !important;
                                        font-size: 2.5rem !important;
                                        text-align: center !important;
                                        display: flex !important;
                                        justify-content: center !important;
                                        align-items: center !important;
                                        width: 100% !important;
                                        letter-spacing: 0.15em !important;
                                        text-shadow: 0 0 25px rgba(16, 185, 129, 0.6) !important;
                                        padding: 1.5rem 0 !important;
                                    `;
                                }
                            });
                        }

                        // Aplicar cambios inmediatamente
                        aplicarCambios();

                        
                        const observer = new MutationObserver(aplicarCambios);
                        observer.observe(document.body, { 
                            childList: true, 
                            subtree: true 
                        });

                        
                        document.addEventListener('livewire:navigated', aplicarCambios);
                        document.addEventListener('livewire:load', aplicarCambios);
                    });
                </script>
            HTML)
            ->renderHook('styles.end', fn() => <<<'HTML'
                <style>
                    
                    body {
                        background: linear-gradient(135deg, #0f172a, #1e293b);
                        color: #f1f5f9;
                    }

                    
                    .fi-sidebar {
                        background-color: #111827 !important;
                        border-right: 1px solid rgba(255, 255, 255, 0.05);
                    }

                    .fi-sidebar-item {
                        border-radius: 8px;
                        transition: all 0.3s ease;
                    }

                    .fi-sidebar-item:hover {
                        background-color: rgba(16, 185, 129, 0.15) !important;
                        transform: scale(1.02);
                    }

                    /* Cards */
                    .fi-stats-overview-stat {
                        border-radius: 1rem !important;
                        background: rgba(31, 41, 55, 0.9) !important;
                        backdrop-filter: blur(6px);
                        border: 1px solid rgba(255, 255, 255, 0.05);
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
                    }

                    /* Botones */
                    .fi-btn {
                        border-radius: 0.75rem !important;
                        font-weight: 600 !important;
                        transition: all 0.2s ease;
                    }

                    .fi-btn:hover {
                        transform: scale(1.05);
                    }
                </style>
            HTML)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}