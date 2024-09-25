<?php

namespace App\Filament\Widgets;

use App\Models\Customers;
use App\Models\Victim;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class MainPage extends BaseWidget
{
    protected function getStats(): array
    {
        $comp=Customers::where('Company',Auth::user()->company)->first();
        return [
            Stat::make('نظام البيع بالتقسيط',$comp->CompanyName)
                ->color('primary')
                ->description($comp->CompanyNameSuffix),
            Stat::make('مرحباً  ',Auth::user()->name)
                ->color('primary')
                ,


        ];
    }
}
