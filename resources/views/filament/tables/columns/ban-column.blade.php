<div {{ $getExtraAttributeBag() }}>

    @if($record->isBanned())
        <x-filament::icon-button
            icon="heroicon-m-x-mark"
            label=""
            color="danger"
        />
    @else
        <x-filament::icon-button
            icon="heroicon-m-check"
            label=""
            color="success"
        />
    @endif

</div>
