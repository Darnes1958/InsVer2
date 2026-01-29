<?php

namespace App\Filament\Resources\Aksat\LongPeriods\Tables;

use App\Enums\Status;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LongPeriodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(false)
            ->columns([
                TextColumn::make('main_no')->searchable()->sortable(),
                TextColumn::make('main.name')->searchable()->sortable(),
                TextColumn::make('status')
                    ->action(function (Model $record){


                        if ($record->status->value==1) $record->status=0;
                            else $record->status=1;
                        $record->save();

                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(Status::class)->label('الحالة')
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
