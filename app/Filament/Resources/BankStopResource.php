<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankStopResource\Pages;
use App\Filament\Resources\BankStopResource\RelationManagers;
use App\Livewire\Traits\PublicTrait;
use App\Models\aksat\BankStop;
use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use App\Models\bank\BankTajmeehy;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;

class BankStopResource extends Resource
{
    use PublicTrait;
    protected static ?string $model = BankStop::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='مرتبات موقوفة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('no')
                    ->relationship('main','name')
                        ->getOptionLabelFromRecordUsing(fn (Main $record) => "{$record->name} {$record->acc}")
                    ->disableOptionWhen(fn (string $value): bool => BankStop::where('no',$value)->exists())

                        ->preload()
                        ->searchable()
                    ->label('العقد')
                    ->afterStateUpdated(function (Set $set, $state){
                        $set('taj_id',main::find($state)->taj_id);
                    })
                    ->required(),
                Forms\Components\DatePicker::make('stop_date')
                    ->label('تاريخ التوقف')
                    ->required(),
                TextInput::make('notes')
                    ->label('ملاحظات'),
                Hidden::make('taj_id'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::getMy('no'),
                TextColumn::make('main.name')
                   ->label('الاسم'),
                TextColumn::make('BankTajmeehy.TajName')
                    ->label('المصرف التجميعي'),
                self::getMy('stop_date'),
                self::getMy('notes'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('taj_id')
                ->relationship('BankTajmeehy','TajName')
                ->label('المصرف التجميعي'),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('Date1')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('Date2')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('stop_date', '>=', $date),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('stop_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBankStops::route('/'),
            'create' => Pages\CreateBankStop::route('/create'),
            'edit' => Pages\EditBankStop::route('/{record}/edit'),
        ];
    }
}
