<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum SellType: int implements HasLabel,HasColor
{
  case مخزن = 1;
  case صالة = 2;
  case الكل = 3;

  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::مخزن => 'info',
      self::صالة => 'primary',
        self::الكل => 'success',
    };
  }
}


