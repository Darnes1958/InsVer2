<?php

namespace App\Filament\Resources\Masrofat\MasCenters\Tables;

use App\Models\bank\Companies;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MasCentersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(false)
            ->columns([
                TextColumn::make('CenterName')
                 ->searchable()
                 ->sortable(),
                TextColumn::make('Company.CompName')
                 ->action(
                     Action::make('edit_Company')
                         ->modalHeading('تغيير الشركة')
                         ->fillForm(function (Model $record) {
                             return ['company_id'=>$record->company_id];
                         })
                         ->schema([
                             Select::make('company_id')
                              ->required()
                              ->searchable()
                              ->preload()
                              ->options(Companies::all()->pluck('CompName','CompNo')->toArray())
                         ])
                        ->action(function (array $data,Model $record) {
                            $record->company_id=$data['company_id'];
                            $record->save();
                        })
                 )
                 ->searchable()
                 ->sortable(),
            ])
            ->defaultSort('CenterWho')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(function (){return Auth::id()==1;}),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
