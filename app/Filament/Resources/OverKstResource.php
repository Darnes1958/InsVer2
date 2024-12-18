<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OverKstResource\Pages;
use App\Filament\Resources\OverKstResource\RelationManagers;
use App\Livewire\Traits\PublicTrait;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\OverKst;
use App\Models\OverTar\over_kst;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OverKstResource extends Resource
{
    use PublicTrait;




    protected static ?string $model = over_kst::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::getMy('no'),
                self::getMy('name'),
                self::getMy('acc'),
                self::getMy('tar_date'),
                self::getMy('kst'),
            ])
            ->recordUrl(function ($record) {return null;})
            ->filters([
                Filter::make('created_at')
                    ->columnSpan(2)
                    ->columns(2)
                    ->form([
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
                                fn (Builder $query, $date): Builder => $query->whereDate('tar_date', '>=', $data['Date1']),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tar_date', '<=', $data['Date2']),
                            );
                    }),
                Filter::make('By')
                    ->columnSpan(2)
                    ->form([
                        Radio::make('By')
                            ->default('taj')
                            ->afterStateUpdated(function ($state,$livewire){
                                $livewire->by=$state;
                            })
                            ->live()
                            ->hiddenLabel()
                            ->columnSpan(1)
                            ->options([
                                'taj'=>'بالتجميعي',
                                'bank'=>'بفرع المصرف',
                            ]),
                    ]),
                Filter::make('sta')
                    ->columnSpan(2)
                    ->form([
                        Radio::make('letters')
                            ->default(0)

                            ->live()
                            ->hiddenLabel()
                            ->options([
                                0=>'غير مرحلة',
                                1=>'مرحلة',
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['letters'],
                                fn (Builder $query): Builder => $query->where('letters',$data['letters']),
                            );
                    }),
                Filter::make('taj')
                    ->columnSpan(2)
                    ->columns(2)
                    ->form([
                        Select::make('taj_id')
                            ->options(BankTajmeehy::all()->pluck('TajName', 'TajNo'))
                            ->visible(function ($livewire) { return $livewire->by=='taj';})
                            ->columnSpan(2)
                            ->live()
                            ->label('المصرف التجميعي'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['taj_id'],
                                fn (Builder $query): Builder => $query->whereIn('bank',bank::where('bank_tajmeeh',$data['taj_id'])->pluck('bank_no')),
                            );
                    }),
                Filter::make('bank')
                    ->columnSpan(6)
                    ->columns(4)
                    ->form([
                        Select::make('bank_id')
                            ->options(bank::all()->pluck('bank_name', 'bank_no'))
                            ->columnSpan(2)
                            ->live()
                            ->visible(function ($livewire) { return $livewire->by=='bank';})
                            ->label('فرع المصرف'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['bank_id'],
                                fn (Builder $query): Builder => $query->where('bank',$data['bank_id']),
                            );
                    }),


            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(8)
            ->actions([
               //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOverKsts::route('/'),
            'create' => Pages\CreateOverKst::route('/create'),
            'edit' => Pages\EditOverKst::route('/{record}/edit'),
        ];
    }
}
