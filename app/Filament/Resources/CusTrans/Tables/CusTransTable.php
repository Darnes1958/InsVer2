<?php

namespace App\Filament\Resources\CusTrans\Tables;

use App\Enums\CusValType;
use App\Models\Account;
use App\Models\Customer;
use App\Models\OurCompany;
use App\Models\Tasneeh;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Svg\Tag\Text;

class CusTransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transable_type')->sortable(),
                TextColumn::make('transable_id')->sortable(),
                TextColumn::make('transable.Company'),
                TextColumn::make('TransDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('Val')
                    ->numeric('0','.',',')
                    ->summarize(Sum::make()->label('')->numeric('0','.',','))
                    ->sortable(),
                TextColumn::make('ValType'),
                TextColumn::make('Notes')
                    ->searchable(),
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
                SelectFilter::make('ValType')
                 ->options(CusValType::class),
                Filter::make('TransDate')
                    ->schema([
                        DatePicker::make('Date1')
                            ->label('من تاريخ'),
                        DatePicker::make('Date2')
                            ->label('إلي تاريخ'),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['Date1'] && ! $data['Date2']) { return null;   }
                        if ( $data['Date1'] && !$data['Date2'])
                            return 'ادخلت بتاريخ  ' . Carbon::parse($data['Date1'])->toFormattedDateString();
                        if ( !$data['Date1'] && $data['Date2'])
                            return 'حتي تاريخ  ' . Carbon::parse($data['Date2'])->toFormattedDateString();
                        if ( $data['Date1'] && $data['Date2'])
                            return 'ادخلت في الفترة من  ' . Carbon::parse($data['Date1'])->toFormattedDateString()
                                .' إلي '. Carbon::parse($data['Date1'])->toFormattedDateString();

                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Date1'],
                                fn (Builder $query, $date): Builder => $query->whereDate('TransDate', '>=', $date),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('TransDate', '<=', $date),
                            );
                    }),
                Filter::make('Company')
                 ->schema([
                     MorphToSelect::make('transable')
                         ->types([
                             MorphToSelect\Type::make(Customer::class)
                                 ->titleAttribute('Company'),
                             MorphToSelect\Type::make(OurCompany::class)
                                 ->titleAttribute('Company'),
                             MorphToSelect\Type::make(Account::class)
                                 ->titleAttribute('Company'),
                             MorphToSelect\Type::make(Tasneeh::class)
                                 ->titleAttribute('Company'),

                         ])
                 ])
                 ->query(function (Builder $query, array $data): Builder {
                  return $query
                      ->when($data['transable_type'],
                      fn(Builder $query): Builder => $query->where('transable_type', $data['transable_type'])
                                                                 ->where('transable_id', $data['transable_id']));

               })
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
