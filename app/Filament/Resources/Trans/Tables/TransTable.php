<?php

namespace App\Filament\Resources\Trans\Tables;

use App\Enums\ImpExp;
use App\Enums\TranType;
use App\Livewire\Traits\PublicTrait;
use App\Models\jeha\jeha;
use App\Models\Receipt;
use App\Models\trans\trans;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TransTable
{
    use PublicTrait;
    public static function configure(Table $table): Table
    {
        return $table

           ->defaultSort('tran_no','desc')

            ->columns([
                TextColumn::make('tran_no')->sortable(),
                TextColumn::make('tran_date')->sortable(),
                TextColumn::make('Jehatable.jeha_name')->sortable()->searchable(),
                TextColumn::make('val'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit_tran')
                    ->iconButton()
                    ->icon(Heroicon::Pencil)
                    ->fillForm(function (trans $record){
                        return $record->toArray();
                    })
                    ->schema([
                        Section::make()
                            ->schema([
                                Radio::make('imp_exp')
                                    ->options(ImpExp::class)
                                    ->default(1)
                                    ->columnSpan(2)
                                    ->hiddenLabel(),
                                Radio::make('tran_type')
                                    ->columnSpan(2)
                                    ->options(TranType::class)->default(1)->hiddenLabel(),
                                Select::make('jeha')
                                    ->options(jeha::where('jeha_type',1)->pluck('jeha_name','jeha_no'))
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2)
                                    ->preload(),
                                TextInput::make('val')
                                    ->numeric()
                                    ->columnSpan(1)
                                    ->gt(0)
                                    ->required(),
                                DatePicker::make('tran_date')
                                    ->default(date('Y-m-d'))
                                    ->columnSpan(1)
                                    ->required(),

                                TextInput::make('notes')->columnSpanFull(),
                            ])
                            ->columns(4)
                    ])
                    ->action(function (array $data,trans $record,$livewire) {
                        $livewire->validate();
                        $record->update([
                           'tran_date' => $data['tran_date'],
                           'jeha' => $data['jeha'],
                           'val' => $data['val'],
                            'tran_type' => $data['tran_type'],
                            'imp_exp' => $data['imp_exp'],
                            'notes' => $data['notes'],
                            'emp'=>Auth::user()->empno,
                        ]);

                    }),
                DeleteAction::make()->iconButton(),
                Action::make('prn')
                    ->iconButton()
                    ->icon(Heroicon::Printer)
                    ->color('blue')
                    ->action(function (trans $record) {

                        return Response::download(self::ret_spatie($record,
                            'PrnView.amma.pdf-Ical',[

                            ]
                        ), 'filename.pdf', self::ret_spatie_header());

                    })
            ])
            ->toolbarActions([
                //
            ]);
    }
}
