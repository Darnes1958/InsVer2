<x-filament-panels::page>
    <div x-data class="flex w-full">

        <div    class="w-6/12 mx-3 ">
            <div >
                {{ $this->mainArcInfolist }}
                <div class="mt-2">
                    @livewire(\App\Livewire\Aksat\Rep\MainItemArc::class)
                </div>
            </div>

            <div x-show="$wire.showOver>0" class="mt-2">
                @livewire(\App\Livewire\Aksat\Rep\OverKstArc::class)
            </div>
            <div  x-show="$wire.showTar>0" class="mt-2">
                @livewire(\App\Livewire\Aksat\Rep\TarKstArc::class)
            </div>

        </div>
        <div   class="w-6/12 ">
            @livewire(\App\Livewire\Aksat\Rep\KstTranArc::class)
        </div>
    </div>
</x-filament-panels::page>
