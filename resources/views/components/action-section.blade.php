<div {{ $attributes->merge(['class' => 'card p-1']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="card-body">
        <div class="">
            {{ $content }}
        </div>
    </div>

    {{-- <div class="card-footer">
        {{ $footer }}
    </div> --}}
</div>
