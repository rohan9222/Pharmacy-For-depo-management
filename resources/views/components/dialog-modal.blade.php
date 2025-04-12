@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-2 py-1">
        <div class="fs-3 fw-bold">
            {{ $title }}
        </div>

        <div class="mt-3 fs-5 text-body-secondary">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-2 py-1 text-end">
        {{ $footer }}
    </div>
</x-modal>
