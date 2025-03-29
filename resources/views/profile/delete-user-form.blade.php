<x-action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <!-- Button trigger modal -->
        <div class="mt-1">
            <x-danger-button wire:loading.attr="disabled" data-bs-toggle="modal" data-bs-target="#targetDeleteModal">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
        
        <!-- Delete User Confirmation Modal -->
        <div class="modal fade" id="targetDeleteModal" tabindex="-1" aria-labelledby="targetDeleteModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="targetDeleteModalLabel">{{ __('Delete Account') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                            <x-input type="password" class="mt-1 block w-75"
                                        autocomplete="current-password"
                                        placeholder="{{ __('Password') }}"
                                        x-ref="password"
                                        wire:model="password"
                                        wire:keydown.enter="deleteUser" />

                            <x-input-error for="password" class="mt-2" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <x-danger-button wire:click="deleteUser" wire:loading.attr="disabled">
                            {{ __('Delete Account') }}
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-action-section>
