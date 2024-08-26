<x-filament-panels::page>
  <div x-data class="flex w-full">
      <div class="w-4/12">
          {{$this->searchForm}}
          <div>
              @livewire(\App\Livewire\Aksat\Rep\MainSearch::class)
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
      </div>
      <div  x-show="$wire.showInfo" class="w-4/12 mx-0 p-0">
          @livewire(\App\Livewire\Aksat\Rep\KstTran::class)

      </div>
  </div>

</x-filament-panels::page>
