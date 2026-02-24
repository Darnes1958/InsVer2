<?php

namespace App\Filament\Resources;

use App\Filament\Tables\ItemTable;
use Filament\Actions\Action;
use Filament\Forms\Components\TableSelect;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\BankStopResource\Pages\ListBankStops;
use App\Filament\Resources\BankStopResource\Pages\CreateBankStop;
use App\Filament\Resources\BankStopResource\Pages\EditBankStop;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel='مرتبات موقوفة';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                DatePicker::make('stop_date')
                    ->label('تاريخ التوقف')
                    ->default(date('Y-m-d'))
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
                SelectFilter::make('taj_id')
                ->relationship('BankTajmeehy','TajName')
                ->label('المصرف التجميعي'),

                Filter::make('created_at')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('stop_date', '>=', $date),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('stop_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
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
            'index' => ListBankStops::route('/'),
            'create' => CreateBankStop::route('/create'),
            'edit' => EditBankStop::route('/{record}/edit'),
        ];
    }
}
