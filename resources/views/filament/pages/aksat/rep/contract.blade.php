<x-filament-panels::page>
  <div class="flex w-full">
      <div class="w-4/12">
          {{$this->searchForm}}
          <div>
              @livewire(\App\Livewire\Aksat\Rep\MainSearch::class)
          </div>
      </div>
      <div x-data  x-show="$wire.showInfo" class="w-4/12 mx-3">
          {{ $this->mainInfolist }}
      </div>
      <div x-data  x-show="$wire.showInfo" class="w-4/12 ">
          @livewire(\App\Livewire\Aksat\Rep\KstTran::class)
      </div>
  </div>

</x-filament-panels::page>
