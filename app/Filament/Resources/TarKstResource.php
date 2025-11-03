<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TarKstResource\Pages\ListTarKsts;
use App\Filament\Resources\TarKstResource\Pages\CreateTarKst;
use App\Filament\Resources\TarKstResource\Pages\EditTarKst;
use App\Filament\Resources\TarKstResource\Pages\CreateTarArc;
use App\Enums\TarType;
use App\Filament\Resources\TarKstResource\Pages;
use App\Filament\Resources\TarKstResource\RelationManagers;
use App\Livewire\Traits\PublicTrait;
use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use App\Models\OverTar\tar_kst;
use App\Models\TarKst;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Filter;

class TarKstResource extends Resource
{
    use PublicTrait;
    protected static ?string $model = tar_kst::class;
    protected static string | \UnitEnum | null $navigationGroup='فائض وترجيع';
    protected static ?string $pluralLabel='ترجيع اقساط ومبالغ';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label='ترجيع اقساط ومبالغ';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('فائض وترجيع');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

               DatePicker::make('tar_date')
                   ->default(now())
                ->label('التاريخ'),
               Select::make('no')
                   ->visible(function ($operation){
                       return $operation=='create';
                   })
                ->options(main::all()->pluck('name', 'no'))

                   ->live()
                   ->afterStateUpdated(function ($state,Set $set,Get $get){
                           $main=main::where('no',$get('no'))->first();
                       $set('bank',$main->bank);
                       $set('acc',$main->acc);
                       $set('name',$main->name);

                   })
                ->searchable()
                ->preload()
                ->required()
                ->label('رقم العقد'),

                TextInput::make('kst')
                 ->label('المبلغ')
                ->required()
                ->numeric()
                ->minValue(1),
                Hidden::make('name'),
                Hidden::make('bank'),
                Hidden::make('acc'),
                Hidden::make('inp_date')->default(now()),
                Hidden::make('tar_type')->default(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::getMy('no'),
                self::getMy('name'),
                self::getMy('acc'),
                self::getMy('kst'),
                self::getMy('tar_date'),
                self::getMy('tar_type'),
            ])
            ->defaultSort('tar_date','desc')
            ->filters([
                SelectFilter::make('tar_type')
                 ->label('نوع الترجيع')
                 ->options(TarType::class),
                Tables\Filters\Filter::make('tar_date')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('tar_date', '>=', $date),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tar_date', '<=', $date),
                            );
                    })

            ])
            ->recordActions([
                EditAction::make()
                 ->visible(function ($record) {return $record->tar_type->value==4;}),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => ListTarKsts::route('/'),
            'create' => CreateTarKst::route('/create'),
            'edit' => EditTarKst::route('/{record}/edit'),
            'createarc'=>CreateTarArc::route('/createarc')
        ];
    }
}
