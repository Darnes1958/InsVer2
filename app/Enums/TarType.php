<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum TarType: int implements HasLabel,HasColor
{
  case من_الفائض = 1;
  case من_الخطأ = 2;
  case من_عقد = 3;
  case ترجيع_مبلغ = 4;


  public function getLabel(): ?string
  {
      return match ($this) {
          self::من_الفائض => 'من الفائض',
          self::من_الخطأ => 'من الخطأ',
          self::من_عقد => 'من عقد',
          self::ترجيع_مبلغ => 'ترجيع مبلغ',
      };
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::من_الفائض => 'info',
      self::من_الخطأ => 'primary',
        self::من_عقد => 'success',
        self::ترجيع_مبلغ => 'warning',


    };
  }
}


