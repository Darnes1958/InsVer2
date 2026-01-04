<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum CusValType: int implements HasLabel,HasColor
{
  case ترحيل_اقساط = 1;
  case ايجار_سيرفر = 2;
  case بيع_منظومة = 3;



  public function getLabel(): ?string
  {
      return str_replace('_',' ',$this->name) ;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::ترحيل_اقساط => 'info',
      self::ايجار_سيرفر => 'primary',
        self::بيع_منظومة => 'success',



    };
  }
}


