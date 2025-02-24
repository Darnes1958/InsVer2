<?php

namespace App\Filament\Resources;

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
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SebastianBergmann\CodeCoverage\Filter;

class TarKstResource extends Resource
{
    use PublicTrait;
    protected static ?string $model = tar_kst::class;
    protected static ?string $pluralLabel='ترجيع اقساط ومبالغ';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label='ترجيع اقساط ومبالغ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Radio::make('fromWho')
                ->hiddenLabel()
                ->live()
                ->visible(function ($operation){
                    return $operation=='create';
                })
                ->dehydrated(false)
                ->options([
                   'main'=>'من القائم',
                    'MainArc'=>'من الأرشيف',
                ])
                   ->default('main')
                ->afterStateUpdated(function (Forms\Set $set){
                    $set('kst',null);
                    $set('no',null);
                }),
               DatePicker::make('tar_date')
                ->label('التاريخ'),
               Select::make('no')
                   ->visible(function ($operation){
                       return $operation=='create';
                   })
                ->options(function (Forms\Get $get){
                    if ($get('fromWho')=='main')
                     return   main::all()->pluck('name', 'no');
                    else
                     return   MainArc::all()->pluck('name', 'no');
                })
                   ->live()
                   ->afterStateUpdated(function ($state,Forms\Set $set,Forms\Get $get){
                       if ($get('fromWho')=='main')
                           $main=main::where('no',$get('no'))->first();
                       if ($get('fromWho')=='MainArc')
                           $main=MainArc::where('no',$get('no'))->first();
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
                Tables\Filters\SelectFilter::make('tar_type')
                 ->label('نوع الترجيع')
                 ->options(TarType::class),
                Tables\Filters\Filter::make('tar_date')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('tar_date', '>=', $date),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tar_date', '<=', $date),
                            );
                    })

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                 ->visible(function ($record) {return $record->tar_type->value==4;}),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTarKsts::route('/'),
            'create' => Pages\CreateTarKst::route('/create'),
            'edit' => Pages\EditTarKst::route('/{record}/edit'),

        ];
    }
}
