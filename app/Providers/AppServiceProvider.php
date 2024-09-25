<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('@livewire(\'top-bar\')'),
        );

        Table::$defaultNumberLocale = 'nl';
        FilamentColor::register([
            'Fuchsia' =>  Color::Fuchsia,
            'green' =>  Color::Green,
            'blue' =>  Color::Blue,
            'gray' =>  Color::Gray,
        ]);
      LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
          ->locales(['ar','en',]); // also accepts a closure
      });
      Model::unguard();
    }
}
