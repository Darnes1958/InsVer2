<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum Mosahah: int implements HasLabel,HasColor
{
    case مصحح = 2;
  case مرجع = 1;

  case غير_مصحح = 0;



  public function getLabel(): ?string
  {
    return str_replace('_',' ',$this->name) ;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::مصحح => 'success',
      self::غير_مصحح => 'primary',
        self::مرجع=>'info'


    };
  }
}


