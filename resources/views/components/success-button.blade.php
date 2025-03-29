<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-success border rounded fw-semibold text-uppercase shadow-sm dark-mode']) }}>
    {{ $slot }}
</button>
