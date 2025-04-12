@props(['for'])

@error($for)
    <span {{ $attributes->merge(['class' => 'text-danger']) }}>{{ $message }}</span>
@enderror
