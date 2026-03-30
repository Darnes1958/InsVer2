<x-filament-panels::page>
    {{$this->form}}
    <div wire:loading class="text-primary-400">
        يرجي الإنتظار ...
    </div>
    {{$this->table}}
</x-filament-panels::page>

