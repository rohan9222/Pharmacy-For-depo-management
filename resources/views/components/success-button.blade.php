<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-success']) }}>
    {{ $slot }}
</button>
