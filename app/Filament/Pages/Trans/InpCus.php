<?php

namespace App\Filament\Pages\Trans;

use App\Enums\ImpExp;
use App\Enums\TranType;
use App\Models\jeha\jeha;
use App\Models\trans\trans;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class InpCus extends Page implements HasSchemas
{
    use InteractsWithSchemas;
    protected string $view = 'filament.pages.trans.inp-cus';

    public $tran_data;
    public function inp_tran(Schema $schema): Schema {
        return    $schema
         ->model(trans::class)
         ->statePath($this->tran_data)
         ->columns([
           Section::make()
            ->schema([
                Hidden::make('tran_no'),
                Hidden::make('tran_who')->default(1),
                Hidden::make('chk_no')->default(0),
                Hidden::make('kyde')->default(0),
                Hidden::make('order_no')->default(0),
                Hidden::make('bank')->default(0),
                Hidden::make('emp')->default(Auth::user()->empno),
                Hidden::make('inp_date')->date('Y-m-d'),
                Radio::make('imp_exp')
                 ->options(ImpExp::class)
                 ->default(1)
                 ->hiddenLabel(),
                Select::make('jeha')
                 ->options(jeha::where('jeha_type',1)->pluck('jeha_name','jeha_no'))
                 ->searchable()
                 ->required()
                 ->preload(),
                TextInput::make('val')
                 ->numeric()
                 ->gt(0)
                 ->required(),
                DatePicker::make('tran_date')
                ->default(\Illuminate\Support\now())
                ->required(),
                Radio::make('tran_type')
                 ->options(TranType::class)->default(1),
                TextInput::make('notes'),





            ])->columns(2)
         ]);
    }


}
