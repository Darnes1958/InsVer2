<?php

namespace App\Filament\Pages\Amma;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use App\Livewire\Traits\PublicTrait;
use App\Models\aksat\main_sells_bank_view;
use App\Models\stores\item_price_sell;
use App\Models\stores\items;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class RepPrices extends Page  implements HasForms
{
    use InteractsWithForms;
    use PublicTrait;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';



    protected ?string $heading='استفسار عن الاسعار لصنف';
    protected static ?string $navigationLabel='استفسار عن الاسعار لصنف';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('استفسار عن الاسعار');
    }
    protected string $view = 'filament.pages.amma.rep-prices';

    public $item_no;
    public $price_sell;
    public function mount(): void
    {
        $this->form->fill(['price_sell'=>0]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_no')
                 ->label('الصنف')
                 ->searchable()
                 ->preload()
                 ->live()
                 ->options(items::where('raseed','>',0)->pluck('item_name','item_no'))
                 ->afterStateUpdated(function ($state,Set $set) {
                     $this->item_no = $state;
                     info($state);
                     info(items::find($state)->price_sell);

                     $set('price_sell',items::find($state)->price_sell);
                 }),

                TextInput::make('price_sell')
                    ->label('السعر نقدا')
                 ->readOnly()
            ])->columns(4);
    }


}
