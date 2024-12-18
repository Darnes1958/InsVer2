<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum BankTaj: string implements HasLabel,HasColor
{
  case بالتجميعي = 'taj';
  case بالفروع = 'bank';



  public function getLabel(): ?string
  {
    return $this->name;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::بالتجميعي => 'info',
      self::بالفروع => 'primary',


    };
  }
}


