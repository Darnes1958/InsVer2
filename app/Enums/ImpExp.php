<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum ImpExp: int implements HasLabel,HasColor
{
  case قبض = 1;
  case دفع = 2;



  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::قبض => 'info',
      self::دفع => 'primary',


    };
  }
}


