<x-filament-panels::page>
  <div x-data class="flex w-full">
      <div class="w-4/12">
          {{$this->searchForm}}
          <div>
              @livewire(\App\Livewire\Aksat\Rep\MainSearch::class)
          </div>
          <div  x-show="$wire.showArc" class="mt-2">
              @livewire(\App\Livewire\Aksat\Rep\ContArc::class)
          </div>

      </div>
      <div    class="w-4/12 mx-3 p-0">
          <div x-show="$wire.showInfo">
              {{ $this->mainInfolist }}
              <div class="mt-2">
                  @livewire(\App\Livewire\Aksat\Rep\MainItem::class)
              </div>
          </div>

          <div x-show="$wire.showOver" class="mt-2">
              @livewire(\App\Livewire\Aksat\Rep\OverKst::class)
          </div>
          <div x-show="$wire.showTar" class="mt-2">
              @livewire(\App\Livewire\Aksat\Rep\TarKst::class)
          </div>

      </div>
      <div  x-show="$wire.showInfo" class="w-4/12 mx-0 p-0">
          @livewire(\App\Livewire\Aksat\Rep\KstTran::class)
      </div>
  </div>
    <x-filament::modal id="mymainModal" slide-over width="6xl" sticky-header>

        <x-slot name="heading">
            <div x-data class="flex">
               <div>عقد من الأرشيف رقم :  {{$arcNo}}</div>
                <div x-show="$wire.arcOver>0" style="margin-right: 10px;color: #00bb00"> اقساط بالفائض {{$arcOver}}</div>
            </div>

        </x-slot>

        @livewire(\App\Filament\Pages\Aksat\Rep\MainArcModal::class)        {{-- Modal content --}}
    </x-filament::modal>

</x-filament-panels::page>
