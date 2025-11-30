<?php

namespace App\Filament\Pages;

use App\Enums\PerType;
use App\Models\masr\MasCenters;
use App\Models\stores\halls_names;
use App\Models\stores\store_exp;
use App\Models\stores\store_exp_view;
use App\Models\stores\stores_names;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use UnitEnum;

class RepPer extends Page implements HasTable
{
    use InteractsWithTable;
    protected string $view = 'filament.pages.rep-per';

    public function getTableRecordKey(Model|array $record): string
    {
        return $record->per_no;
    }
    protected ?string $heading='';
    protected static ?string $navigationLabel='تقرير عن أذونات الصرف';
    protected static string | UnitEnum | null $navigationGroup='مخازن';

    public $who,$who2,$st_no,$st_no2;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                return store_exp::query();
            })
            ->defaultSort('per_no','desc')
            ->columns([
                TextColumn::make('per_type'),
                TextColumn::make('exp_date'),
                TextColumn::make('per_no')
                ->action(
                    Action::make('perno')
                    ->modalHeading(false)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn (Action $action) => $action->label('عودة'))
                    ->modalContent(
                        function ($state) {
                            return
                                 view(
                                    'filament.pages.views.view-per-tran-widget',
                                    ['per_no' => $state],
                                );

                        }
                    )
                ),
                TextColumn::make('st_no')->label('من')
                ->state(function ($record) {
                    if (in_array($record->per_type,[1,2])) return stores_names::find($record->st_no)->st_name;
                    else return halls_names::find($record->st_no)->hall_name;
                }),
                TextColumn::make('st_no2')->label('الي')
                ->state(function ($record) {
                    if (in_array($record->per_type,[1,3])) return stores_names::find($record->st_no2)->st_name;
                    else return halls_names::find($record->hall_no)->hall_name;
                }),
            ])
            ->filters([
                SelectFilter::make('per_type')
                    ->label('البيان')
                    ->options(PerType::class),

                Filter::make('exp_date')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('exp_date', '>=', $data['Date1']),
                            )
                            ->when(
                                $data['Date2'],
                                fn (Builder $query, $date): Builder => $query->whereDate('exp_date', '<=', $data['Date2']),
                            );
                    }),
                Filter::make('CenterNo')
                    ->schema([
                        Select::make('CenterName')
                            ->label('من')
                            ->options(MasCenters::where('WhoID','!=',0)->pluck('CenterName','CenterNo')->all())
                            ->afterStateUpdated(function ($state){

                                $res=MasCenters::find($state);
                                $this->st_no=$res->WhoID;
                                $this->who=$res->CenterWho;

                            })
                            ->searchable()
                            ->preload()
                    ])
                    ->indicateUsing(function (array $data): ?string {
                            return 'من  ' ;
                    })
                    ->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when($this->who==2,
                                fn (Builder $query, $data): Builder => $query->whereIn('per_type', [1,2])->where('st_no',$this->st_no),
                            )
                            ->when(
                                $this->who==1,
                                fn (Builder $query, $data): Builder => $query->whereIn('per_type', [3,4])->where('st_no',$this->st_no),
                            );
                    }),
                Filter::make('CenterNo2')
                    ->schema([
                        Select::make('CenterName2')
                            ->label('من')
                            ->options(MasCenters::where('WhoID','!=',0)->pluck('CenterName','CenterNo')->all())
                            ->afterStateUpdated(function ($state){
                                $res=MasCenters::find($state);
                                $this->st_no2=$res->WhoID;
                                $this->who2=$res->CenterWho;

                            })
                            ->searchable()
                            ->preload()
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        return 'إلي  ' ;
                    })
                    ->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when($this->who2==2,
                                fn (Builder $query, $data): Builder => $query->whereIn('per_type', [1,3])->where('st_no',$this->st_no2),
                            )
                            ->when(
                                $this->who2==1,
                                fn (Builder $query, $data): Builder => $query->whereIn('per_type', [2,4])->where('hall_no',$this->st_no2),
                            );
                    })

            ])

            ->recordActions([

                Action::make('عرض ')
                    ->modalHeading(false)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn (Action $action) => $action->label('عودة'))
                    ->modalContent(fn (Model $record): View => view(
                        'filament.pages.views.view-per-tran-widget',
                        ['per_no' => $record->per_no],
                    ))

                    ->icon('heroicon-o-eye')
                    ->iconButton(),
            ]);
    }
}
