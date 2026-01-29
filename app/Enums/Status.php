<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Icons\Heroicon;

enum Status: int implements HasLabel,HasColor
{
    case غير_فعال = 0;
  case فعال = 1;





  public function getLabel(): ?string
  {
    return str_replace('_',' ',$this->name) ;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::فعال => 'success',
      self::غير_فعال => 'primary',

    };
  }
  public function getIcon(): string | BackedEnum | Htmlable | null
  {
      return match ($this) {
        self::فعال  =>Heroicon::Check,
          self::غير_فعال =>Heroicon::XMark,
      };
  }
}


