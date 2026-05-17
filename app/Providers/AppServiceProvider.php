<?php

namespace App\Providers;


use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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


        FilamentColor::register([
            'Fuchsia' =>  Color::Fuchsia,
            'green' =>  Color::Green,
            'blue' =>  Color::Blue,
            'gray' =>  Color::Gray,
        ]);
        DeleteAction::configureUsing(fn (DeleteAction $action) => $action->modalHeading('حذف السجل'));
        Table::configureUsing(fn(Table $table) => $table
            ->defaultNumberLocale('nl')
            ->pluralModelLabel('الصفحات')
            ->emptyStateHeading('لا توجد بيانات')
            ->defaultKeySort(false)
        );
        CreateAction::configureUsing(fn(CreateAction $createAction) => $createAction->label('إضافة'));

        Radio::configureUsing(function (Radio $radio): void {
            $radio->inline()->inlineLabel()->translateLabel();
        });
        TextInput::configureUsing(function (TextInput $input): void {
            $input->translateLabel();
        });
        DatePicker::configureUsing(function (DatePicker $input): void {
            $input->translateLabel();
        });

        TextColumn::configureUsing(function (TextColumn $column): void {
            $column->translateLabel();
        });
        IconColumn::configureUsing(function (IconColumn $column): void {
            $column->translateLabel();
        });
        Select::configureUsing(function (Select $column): void {
            $column->translateLabel();
        });
        TextEntry::configureUsing(function (TextEntry $entry): void {$entry->translateLabel();});

      Model::unguard();
    }
}
