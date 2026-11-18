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
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('SISMANTIK - Media Intelligence Kab. Jombang')
            ->brandLogo(fn () => view('filament.components.brand-logo'))
            ->brandLogoHeight('2.85rem')
            ->font('Plus Jakarta Sans')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)
            ->colors([
                'primary' => Color::Emerald,
                'danger' => Color::Rose,
                'warning' => Color::Amber,
                'info' => Color::Sky,
                'success' => Color::Green,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\ExecutiveDashboard::class,
                \App\Filament\Pages\PetaGeospasial::class,
            ])
            ->renderHook(
                'panels::user-menu.before',
                fn () => view('filament.components.topbar-end')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => '
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
                    <link rel="stylesheet" href="/vendor/leaflet/leaflet.css" />
                    <script src="/vendor/leaflet/leaflet.js"></script>
                    <script>
                        if (!localStorage.getItem("theme")) {
                            localStorage.setItem("theme", "dark");
                            document.documentElement.classList.add("dark");
                        }
                    </script>
                    <style>
                        :root {
                            --font-family: "Plus Jakarta Sans", system-ui, sans-serif;
                        }
                        body, .fi-body {
                            font-family: "Plus Jakarta Sans", system-ui, -apple-system, sans-serif !important;
                            letter-spacing: -0.01em;
                        }
                        .font-mono, [data-stat-value] {
                            font-family: "JetBrains Mono", monospace !important;
                        }

                        /* ========================================================
                           LIGHT MODE LUXURY EXECUTIVE THEME
                           ======================================================== */
                        html:not(.dark) body, html:not(.dark) .fi-layout {
                            background-color: #f8fafc !important;
                            background-image: 
                                radial-gradient(circle at 10% 10%, rgba(16, 185, 129, 0.04) 0%, transparent 45%),
                                radial-gradient(circle at 90% 90%, rgba(6, 182, 212, 0.04) 0%, transparent 45%),
                                radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.02) 0%, transparent 60%) !important;
                            background-attachment: fixed !important;
                        }

                        html:not(.dark) .fi-sidebar {
                            background: #ffffff !important;
                            border-right: 1px solid rgba(226, 232, 240, 0.9) !important;
                            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.03) !important;
                        }

                        html:not(.dark) .fi-sidebar-item-active > a {
                            background: linear-gradient(90deg, rgba(16, 185, 129, 0.12) 0%, rgba(16, 185, 129, 0.02) 100%) !important;
                            border-left: 3.5px solid #059669 !important;
                            color: #047857 !important;
                            font-weight: 700 !important;
                            box-shadow: inset 0 0 12px rgba(16, 185, 129, 0.06) !important;
                        }

                        html:not(.dark) .fi-topbar {
                            background-color: rgba(255, 255, 255, 0.9) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            border-bottom: 1px solid rgba(226, 232, 240, 0.9) !important;
                        }

                        /* Light Mode Custom Tables */
                        html:not(.dark) .fi-ta,
                        html:not(.dark) .fi-ta-ctn {
                            background: #ffffff !important;
                            border-radius: 1.25rem !important;
                            border: 1px solid rgba(226, 232, 240, 0.95) !important;
                            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 0 0 1px rgba(226, 232, 240, 0.7) !important;
                            overflow: hidden !important;
                        }

                        html:not(.dark) .fi-ta-header {
                            background-color: #ffffff !important;
                            padding: 1rem 1.25rem !important;
                            border-bottom: 1px solid #f1f5f9 !important;
                        }

                        html:not(.dark) .fi-ta-header-cell {
                            background: #f8fafc !important;
                            border-bottom: 2px solid #e2e8f0 !important;
                            font-family: "Plus Jakarta Sans", system-ui, sans-serif !important;
                            font-weight: 800 !important;
                            font-size: 0.72rem !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.06em !important;
                            color: #475569 !important;
                            padding: 12px 18px !important;
                        }

                        html:not(.dark) .fi-ta-row {
                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            border-bottom: 1px solid #f1f5f9 !important;
                        }

                        html:not(.dark) .fi-ta-row:hover {
                            background-color: #f8fafc !important;
                            border-left: 4px solid #10b981 !important;
                            transform: translateX(2px) !important;
                        }

                        html:not(.dark) .fi-ta-cell {
                            padding: 13px 18px !important;
                            font-size: 0.85rem !important;
                            color: #334155 !important;
                        }

                        html:not(.dark) .fi-ta-search-input,
                        html:not(.dark) input[type="search"] {
                            background-color: #ffffff !important;
                            border: 1.5px solid #cbd5e1 !important;
                            border-radius: 0.75rem !important;
                            font-size: 0.82rem !important;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
                            transition: all 0.2s ease !important;
                        }

                        html:not(.dark) .fi-ta-search-input:focus,
                        html:not(.dark) input[type="search"]:focus {
                            border-color: #10b981 !important;
                            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
                        }

                        html:not(.dark) .fi-ta-pagination {
                            border-top: 1px solid #e2e8f0 !important;
                            background-color: #f8fafc !important;
                            padding: 10px 18px !important;
                        }

                        /* Light Mode Cards, Sections & Widgets */
                        html:not(.dark) .fi-section,
                        html:not(.dark) .fi-card,
                        html:not(.dark) .fi-wi-stats-overview-stat,
                        html:not(.dark) .fi-wi-widget > div {
                            background: #ffffff !important;
                            border: 1px solid rgba(226, 232, 240, 0.9) !important;
                            border-radius: 1.25rem !important;
                            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04) !important;
                            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        html:not(.dark) .fi-section:hover,
                        html:not(.dark) .fi-wi-widget > div:hover {
                            box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.08) !important;
                            border-color: rgba(16, 185, 129, 0.4) !important;
                        }

                        html:not(.dark) .fi-wi-stats-overview-stat:nth-child(1) {
                            border-top: 3.5px solid #06b6d4 !important;
                        }
                        html:not(.dark) .fi-wi-stats-overview-stat:nth-child(2) {
                            border-top: 3.5px solid #10b981 !important;
                        }
                        html:not(.dark) .fi-wi-stats-overview-stat:nth-child(3) {
                            border-top: 3.5px solid #f59e0b !important;
                        }
                        html:not(.dark) .fi-wi-stats-overview-stat:nth-child(4) {
                            border-top: 3.5px solid #f43f5e !important;
                        }

                        /* ========================================================
                           GLOBAL COMPONENT POLISH (BOTH MODES)
                           ======================================================== */
                        /* Modern Pill Badges */
                        .fi-badge {
                            border-radius: 9999px !important;
                            font-weight: 700 !important;
                            font-size: 0.72rem !important;
                            letter-spacing: 0.02em !important;
                            padding: 3px 10px !important;
                        }

                        /* Executive Gradient Primary Buttons */
                        .fi-btn-color-primary {
                            background: linear-gradient(135deg, #10b981 0%, #0d9488 100%) !important;
                            color: #ffffff !important;
                            border: none !important;
                            border-radius: 0.75rem !important;
                            font-weight: 700 !important;
                            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.35) !important;
                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        .fi-btn-color-primary:hover {
                            transform: translateY(-1.5px) scale(1.02) !important;
                            box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.45) !important;
                        }

                        /* Breadcrumbs Polish */
                        .fi-breadcrumbs {
                            font-size: 0.82rem !important;
                            font-weight: 600 !important;
                            margin-bottom: 0.5rem !important;
                        }
                        .fi-breadcrumbs-item:last-child {
                            font-weight: 800 !important;
                            color: #10b981 !important;
                        }

                        /* Page Headers */
                        .fi-header-heading {
                            font-weight: 900 !important;
                            letter-spacing: -0.025em !important;
                            font-size: 1.65rem !important;
                        }
                        .fi-header-subheading {
                            font-size: 0.88rem !important;
                            font-weight: 500 !important;
                            line-height: 1.5 !important;
                        }

                        /* ========================================================
                           DARK MODE EXECUTIVE WAR-ROOM ATMOSPHERE
                           ======================================================== */
                        html.dark body, html.dark .fi-layout {
                            background-color: #070b13 !important;
                            background-image: 
                                radial-gradient(circle at 10% 10%, rgba(16, 185, 129, 0.05) 0%, transparent 45%),
                                radial-gradient(circle at 90% 90%, rgba(6, 182, 212, 0.05) 0%, transparent 45%),
                                radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.03) 0%, transparent 60%) !important;
                            background-attachment: fixed !important;
                        }

                        /* Cyber Sleek Sidebar */
                        html.dark .fi-sidebar {
                            background: linear-gradient(180deg, #090e18 0%, #060911 100%) !important;
                            border-right: 1px solid rgba(255, 255, 255, 0.07) !important;
                            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.4) !important;
                        }
                        html.dark .fi-sidebar-item-active > a {
                            background: linear-gradient(90deg, rgba(16, 185, 129, 0.18) 0%, rgba(16, 185, 129, 0.03) 100%) !important;
                            border-left: 3px solid #10b981 !important;
                            color: #34d399 !important;
                            font-weight: 700 !important;
                            box-shadow: inset 0 0 15px rgba(16, 185, 129, 0.1) !important;
                        }

                        /* Translucent Frosted Topbar */
                        html.dark .fi-topbar {
                            background-color: rgba(9, 14, 24, 0.85) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            border-bottom: 1px solid rgba(255, 255, 255, 0.07) !important;
                        }

                        /* Glassmorphic Cards & Sections */
                        html.dark .fi-section, 
                        html.dark .fi-wi-stats-overview-stat, 
                        html.dark .fi-card, 
                        html.dark .fi-wi-widget > div {
                            background: rgba(13, 20, 36, 0.85) !important;
                            backdrop-filter: blur(14px) !important;
                            -webkit-backdrop-filter: blur(14px) !important;
                            border: 1px solid rgba(148, 163, 184, 0.14) !important;
                            border-radius: 1.25rem !important;
                            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5) !important;
                            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        html.dark .fi-section:hover, 
                        html.dark .fi-wi-widget > div:hover {
                            border-color: rgba(6, 182, 212, 0.3) !important;
                            box-shadow: 0 15px 35px -10px rgba(6, 182, 212, 0.12) !important;
                        }

                        /* Dark Mode Table Enhancements */
                        html.dark .fi-ta,
                        html.dark .fi-ta-ctn {
                            background: rgba(13, 20, 36, 0.9) !important;
                            border-radius: 1.25rem !important;
                            border: 1px solid rgba(148, 163, 184, 0.14) !important;
                            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5) !important;
                            overflow: hidden !important;
                        }

                        html.dark .fi-ta-header-cell {
                            background: rgba(15, 23, 42, 0.95) !important;
                            border-bottom: 2px solid rgba(148, 163, 184, 0.2) !important;
                            color: #94a3b8 !important;
                            font-weight: 800 !important;
                            font-size: 0.72rem !important;
                            letter-spacing: 0.06em !important;
                            text-transform: uppercase !important;
                            padding: 12px 18px !important;
                        }

                        html.dark .fi-ta-row {
                            border-bottom: 1px solid rgba(148, 163, 184, 0.08) !important;
                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        html.dark .fi-ta-row:hover {
                            background-color: rgba(16, 185, 129, 0.06) !important;
                            border-left: 4px solid #34d399 !important;
                            transform: translateX(2px) !important;
                        }

                        html.dark .fi-ta-cell {
                            padding: 13px 18px !important;
                            font-size: 0.85rem !important;
                            color: #e2e8f0 !important;
                        }

                        html.dark .fi-ta-search-input,
                        html.dark input[type="search"] {
                            background-color: rgba(15, 23, 42, 0.8) !important;
                            border: 1px solid rgba(148, 163, 184, 0.2) !important;
                            border-radius: 0.75rem !important;
                            color: #f1f5f9 !important;
                        }

                        html.dark .fi-ta-search-input:focus,
                        html.dark input[type="search"]:focus {
                            border-color: #06b6d4 !important;
                            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2) !important;
                        }

                        html.dark .fi-ta-pagination {
                            border-top: 1px solid rgba(148, 163, 184, 0.12) !important;
                            background-color: rgba(15, 23, 42, 0.6) !important;
                        }

                        /* Top Neon Borders for 4 Executive Stats */
                        html.dark .fi-wi-stats-overview-stat:nth-child(1) {
                            border-top: 3px solid #06b6d4 !important;
                            box-shadow: 0 4px 20px -2px rgba(6, 182, 212, 0.15) !important;
                        }
                        html.dark .fi-wi-stats-overview-stat:nth-child(2) {
                            border-top: 3px solid #10b981 !important;
                            box-shadow: 0 4px 20px -2px rgba(16, 185, 129, 0.15) !important;
                        }
                        html.dark .fi-wi-stats-overview-stat:nth-child(3) {
                            border-top: 3px solid #f59e0b !important;
                            box-shadow: 0 4px 20px -2px rgba(245, 158, 11, 0.15) !important;
                        }
                        html.dark .fi-wi-stats-overview-stat:nth-child(4) {
                            border-top: 3px solid #f43f5e !important;
                            box-shadow: 0 4px 20px -2px rgba(244, 63, 94, 0.15) !important;
                        }

                        .fi-wi-stats-overview-stat:hover {
                            transform: translateY(-3px) scale(1.01) !important;
                        }

                        /* Typography & Table Polish */
                        .fi-ta-header-cell { 
                            font-weight: 700; 
                            text-transform: uppercase; 
                            font-size: 0.72rem; 
                            letter-spacing: 0.05em; 
                        }
                        
                        /* High-Tech Scrollbar */
                        ::-webkit-scrollbar { width: 7px; height: 7px; }
                        ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.2); }
                        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.25); border-radius: 9999px; }
                        ::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.5); }

                        /* Custom Cyber Leaflet Styling */
                        .leaflet-container {
                            background-color: #070b13 !important;
                            font-family: "Plus Jakarta Sans", system-ui, sans-serif !important;
                        }
                        .cyber-leaflet-popup .leaflet-popup-content-wrapper {
                            background: rgba(13, 20, 36, 0.95) !important;
                            backdrop-filter: blur(14px) !important;
                            -webkit-backdrop-filter: blur(14px) !important;
                            border: 1px solid rgba(6, 182, 212, 0.4) !important;
                            border-radius: 1rem !important;
                            color: #f1f5f9 !important;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7) !important;
                            padding: 0 !important;
                        }
                        .cyber-leaflet-popup .leaflet-popup-content {
                            margin: 12px 14px !important;
                            line-height: 1.4 !important;
                        }
                        .cyber-leaflet-popup .leaflet-popup-tip {
                            background: rgba(13, 20, 36, 0.95) !important;
                            border: 1px solid rgba(6, 182, 212, 0.4) !important;
                        }
                        .cyber-leaflet-popup .leaflet-popup-close-button {
                            color: #94a3b8 !important;
                            padding: 6px !important;
                        }
                        .cyber-leaflet-popup .leaflet-popup-close-button:hover {
                            color: #ffffff !important;
                        }
                        .cyber-leaflet-tooltip {
                            background: rgba(15, 23, 42, 0.95) !important;
                            backdrop-filter: blur(8px) !important;
                            border: 1px solid rgba(148, 163, 184, 0.25) !important;
                            border-radius: 0.5rem !important;
                            color: #f8fafc !important;
                            font-size: 11px !important;
                            font-weight: 700 !important;
                            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
                            padding: 4px 8px !important;
                        }
                        .cyber-leaflet-tooltip::before {
                            border-top-color: rgba(15, 23, 42, 0.95) !important;
                        }
                    </style>
                '
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
