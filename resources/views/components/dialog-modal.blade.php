@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-2 py-1">
        <div class="fs-3 fw-semibold text-gray-900 dark:text-gray-100">
            {{ $title }}
        </div>

        <div class="mt-4 fs-6 text-gray-600 dark:text-gray-400">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-2 py-1 bg-gray-100 dark:bg-gray-800 text-end">
        {{ $footer }}
    </div>
</x-modal>
