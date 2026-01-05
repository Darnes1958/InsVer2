<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum SysType: string implements HasLabel,HasColor
{
  case الإصدار_الأول = 'App\Models\Customer';
  case الإصدار_الثاني = 'App\Models\OurCompany';
  case التصنيع = 'App\Models\Tasneeh';
    case المحاسبة = 'App\Models\Account';



  public function getLabel(): ?string
  {
      return str_replace('_',' ',$this->name) ;
  }
  public function getColor(): string | array | null
  {
    return match ($this) {
      self::الإصدار_الأول => 'info',
      self::الإصدار_الثاني => 'primary',
      self::التصنيع => 'success',
        self::المحاسبة => 'danger',



    };
  }
}


