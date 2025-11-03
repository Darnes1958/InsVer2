<?php

namespace App\Filament\Resources\Buys\Tables;

use App\Models\jeha\jeha;
use App\Models\Supplier;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BuysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order_no','desc')
            ->pluralModelLabel('الصفحات')
            ->columns([
                TextColumn::make('order_no')
                    ->searchable()
                    ->sortable()
                    ->label('رقم الفاتورة'),
                TextColumn::make('Jehatable.jeha_name')
                    ->searchable()
                    ->sortable()
                    ->label('اسم المورد'),
                TextColumn::make('order_date')
                    ->searchable()
                    ->sortable()
                    ->label('التاريخ'),
                TextColumn::make('tot')
                    ->searchable()
                    ->sortable()
                    ->summarize(Sum::make()->label('')->numeric('2','.',','))
                    ->label('اجمالي الفاتورة'),
                TextColumn::make('cash')
                    ->summarize(Sum::make()->label('')->numeric('2','.',','))
                    ->label('المدفوع'),
                TextColumn::make('not_cash')->summarize(Sum::make()->label('')->numeric('2','.',','))
                    ->label('الباقي'),
                TextColumn::make('notes')
                    ->label('ملاحظات'),
            ])
            ->filters([
                SelectFilter::make('jeha')
                    ->options(jeha::all()->pluck('jeha_name', 'jeha_no'))
                    ->searchable()
                    ->label('مورد معين'),
                Filter::make('order_date')
                    ->schema([
                        DatePicker::make('Date1')
                            ->label('من تاريخ'),
                        DatePicker::make('Date2')
                            ->label('إلي تاريخ'),
                    ])
                    ->columns(2)
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
                                fn (Builder $query, $date): Builder => $query->whereDate('order_date', '>=', $date),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('order_date', '<=', $date),
                            );
                    })
            ])
          //  ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
