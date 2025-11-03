<?php

namespace App\Livewire\AKsat\Rep;

use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\TextSize;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use App\Enums\KsmType;
use App\Models\aksat\kst_trans;
use App\Models\aksat\main;
use App\Models\Operations;
use App\Models\OverTar\over_kst;
use App\Models\OverTar\tar_kst;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class OverKst extends BaseWidget
{
    public $no;
    protected static ?string $heading="";
    #[On('OverKstNo')]
    public function OverKstNo($no){

        $this->no=$no;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('لا توجد بيانات')
            ->paginated(function (){
                return over_kst::where('no',$this->no)->count()>5;
            })
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5,10,15])
            ->defaultSort('tar_date')
            ->query(function (over_kst $main){
                $main=over_kst::where('no',$this->no);
                return $main;
            })
            ->queryStringIdentifier('OverKst')
            ->columns([
                TextColumn::make('status')
                    ->state(fn(Model $record): string=> $record->letters==1?'مرجع':'غيرمرجع')
                    ->color(fn(Model $record): string=> $record->letters==1?'primary':'info')
                    ->size(TextSize::ExtraSmall)
                    ->label(new HtmlString('<span style="font-size: smaller;color: #00bb00">خصم بالفائض&nbsp;&nbsp;</span>')),
                TextColumn::make('tar_date')
                    ->size(TextSize::ExtraSmall)
                    ->label('التاريخ'),
                TextColumn::make('kst')
                    ->size(TextSize::ExtraSmall)
                    ->label('المبلغ'),

            ])
            ->recordActions([
                Action::make('del')
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->iconSize(IconSize::Small)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(function (Model $record){
                        return $record->letters==0 &&
                            Auth::user()->can('ادخال فائض وترجيع');
                    })
                    ->action(function (Model $record){
                       $record->delete();
                        $this->dispatch('showMe',no: $this->no);
                    }),
                Action::make('tar')
                    ->schema([
                        Section::make([
                            Radio::make('ksm_type')
                                ->hiddenLabel()
                                ->inline()
                                ->columnSpan(2)
                                ->options(KsmType::class),
                            DatePicker::make('tar_date')
                                ->required()
                                ->label('التاريح'),

                        ])

                    ])
                    ->fillForm(fn (): array => [
                        'ksm_type' => KsmType::المصرف,
                        'tar_date'=>date('Y-m-d'),
                    ])
                    ->modalCancelActionLabel('عودة')
                    ->modalSubmitActionLabel('تحزين')
                    ->modalHeading('ترجيع مبلغ')
                    ->modalWidth(Width::Small)
                    ->action(function (array $data,over_kst $record,){
                        $record->update(['letters'=>1]);
                        $main=main::find($this->no)->first();
                        tar_kst::insert([
                            'no' => $this->no,
                            'name' => $main->name,
                            'bank' => $main->bank,
                            'acc' => $main->acc,
                            'kst' => $record->kst,
                            'tar_type' => 1,
                            'tar_date' => $data['tar_date'],
                            'ksm_date' => null,
                            'ser' => 0,
                            'kst_date' => null,
                            'emp' => Auth::user()->empno,
                            'ksm_type' => $data['ksm_type'],
                        ]);
                        $this->dispatch('showMe',no: $this->no);
                    })
                    ->tooltip('ترجيع')
                    ->iconButton()
                    ->iconSize(IconSize::Small)
                    ->icon('heroicon-o-arrow-turn-down-left')
                    ->visible(function (Model $record){
                        return $record->letters==0 &&
                            Auth::user()->can('ادخال فائض وترجيع');
                    })

                    ->color('blue')


            ])
            ;
    }
}
