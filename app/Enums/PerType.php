<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum PerType: int implements HasLabel,HasColor
{
  case من_مخزن_الي_مخزن = 1;
  case من_مخزن_الي_صالة = 2;
  case من_صالة_الي_مخزن = 3;
  case من_صالة_الي_صالة =4;



  public function getLabel(): ?string
  {
      return str_replace('_',' ',$this->name) ;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::من_مخزن_الي_مخزن => 'info',
      self::من_مخزن_الي_صالة => 'primary',
        self::من_صالة_الي_مخزن => 'success',
        self::من_صالة_الي_صالة  => 'warning',



    };
  }
}


