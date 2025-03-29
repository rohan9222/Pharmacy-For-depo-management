<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="row mb-3" x-data="{photoName: null, photoPreview: null}">
                <label for="" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                <div class="col-md-8 col-lg-9">
                    <div class="mt-2" x-show="! photoPreview">
                        <img  class="profileImagePreview img-thumbnail rounded-circle w-25 h-25" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" alt="Profile">
                    </div>
                    
                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <img :src="photoPreview" alt="Profile Photo Preview" class="profileImagePreview img-thumbnail rounded-circle w-25 h-25" />
                    </div>
                    
                    
                    <div class="pt-2">
                        <!-- Profile Photo File Input -->
                        <input 
                            type="file" 
                            id="image" 
                            class="btn btn-info form-control d-none" 
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);" 
                            name="image" 
                            accept="image/*">
                            
                            <x-success-button class="btn-sm p-2 px-3" type="button" x-on:click.prevent="$refs.photo.click()">
                                <i class="bi bi-upload"></i>
                            </x-success-button>
                            
                            @if ($this->user->profile_photo_path)
                                <x-danger-button type="button" class="btn-sm p-2 px-3" wire:click="deleteProfilePhoto">
                                    <i class="bi bi-trash"></i>
                                </x-danger-button>
                            @endif
                        <x-input-error for="photo" class="mt-2" />
                    </div>
                </div>
            </div>
        @endif

        <!-- Name -->
        <div class="row mb-3">
            <x-label class="col-md-4 col-lg-3 col-form-label" for="name" value="{{ __('Name') }}" />
            <div class="col-md-8 col-lg-9">
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
                <x-input-error for="name" class="mt-2" />
            </div>
        </div>

        <!-- Email -->
        <div class="row mb-3">
            <x-label class="col-md-4 col-lg-3 col-form-label" for="email" value="{{ __('Email') }}" />
            <div class="col-md-8 col-lg-9">
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
                <x-input-error for="email" class="mt-2" />
            </div>

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <small class="text-sm mt-2 dark:text-white">
                    {{ __('Your email address is unverified.') }}

                    <a class="btn btn-link p-0 text-sm text-secondary" 
                            wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </a>
                </small>

                @if ($this->verificationLinkSent)
                    <small class="mt-2 font-weight-medium text-success text-sm">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </small>                
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
