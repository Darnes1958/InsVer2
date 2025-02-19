<?php

namespace App\Filament\Pages\Amma;

use App\Livewire\Traits\PublicTrait;
use App\Models\aksat\main_sells_bank_view;
use App\Models\stores\item_price_sell;
use App\Models\stores\items;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class RepPrices extends Page  implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    use PublicTrait;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';



    protected ?string $heading='استفسار عن الاسعار لصنف';
    protected static ?string $navigationLabel='استفسار عن الاسعار لصنف';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('استفسار عن الاسعار');
    }
    protected static string $view = 'filament.pages.amma.rep-prices';

    public $item_no;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_no')
                 ->label('رقم الصنف')
                 ->searchable()
                 ->preload()
                 ->live()
                 ->options(items::all()->pluck('item_name','item_no'))
                 ->afterStateUpdated(function ($state,Set $set) {
                     $this->item_no = $state;

                 }),

            ])->columns(4);
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                return item_price_sell::query()
                    ->where('item_no',$this->item_no);

            })
            ->columns([
                TextColumn::make('price_type')
                    ->label('طريقة الدفع'),
                TextColumn::make('price')
                    ->label('السعر'),
            ])
            ;
    }

}
