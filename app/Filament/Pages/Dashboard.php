<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected ?string $heading="";
    public function getColumns(): int | string | array
    {
        return 2;
    }
}
