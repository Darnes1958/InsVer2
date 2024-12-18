<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum Morahel: int implements HasLabel,HasColor
{
  case مرحل = 1;
  case غير_مرحل = 0;



  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::مرحل => 'info',
      self::غير_مرحل => 'primary',


    };
  }
}


