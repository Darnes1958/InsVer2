<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum KsmType: int implements HasLabel,HasColor
{
  case نقدا = 0;
  case المصرف = 1;
  case صك = 2;
  case الكنروني = 3;


  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::نقدا => 'info',
      self::المصرف => 'primary',
        self::صك => 'success',
        self::الكنروني => 'warning',


    };
  }
}


