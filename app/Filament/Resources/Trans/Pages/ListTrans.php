<?php

namespace App\Filament\Resources\Trans\Pages;

use App\Enums\ImpExp;
use App\Enums\TranType;
use App\Filament\Resources\Trans\TransResource;
use App\Models\jeha\jeha;
use App\Models\trans\trans;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;

class ListTrans extends ListRecords
{
    protected static string $resource = TransResource::class;

    protected ?string $heading='';
    protected function getHeaderActions(): array
    {
        return [
            Action::make('inpCus')
                ->label('إضافة إيصال زبائن')
                ->schema([
                    Section::make()
                        ->schema([
                            Hidden::make('tran_no'),
                            Hidden::make('tran_who')->default(1),
                            Hidden::make('chk_no')->default(0),
                            Hidden::make('kyde')->default(0),
                            Hidden::make('order_no')->default(0),
                            Hidden::make('bank')->default(0),
                            Hidden::make('emp')->default(Auth::user()->empno),
                            Hidden::make('inp_date')->default(date('Y-m-d')),
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
                                ->default(\Illuminate\Support\now())
                                ->columnSpan(1)
                                ->required(),

                            TextInput::make('notes')->columnSpanFull(),
                        ])
                        ->columns(4)
                ])
                ->action(function (array $data) {
                    $this->validate();
                    $data['tran_no']=trans::max('tran_no')+1;
                    trans::create($data);
                }),


        ];
    }
}
