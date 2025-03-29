<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-danger fw-semibold text-uppercase']) }}>
    {{ $slot }}
</button>

