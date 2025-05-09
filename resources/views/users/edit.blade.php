<x-app-layout>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit User
                </div>
                <div class="float-end">
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="address" class="col-md-4 col-form-label text-md-end text-start">Address</label>
                        <div class="col-md-6">
                            <input type="address" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address" value="{{ $user->address }}">
                            @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="territory" class="col-md-4 col-form-label text-md-end text-start">Territory</label>
                        <div class="col-md-6">
                            <input type="territory" list="territories" class="form-control @error('territory') is-invalid @enderror" id="territory" name="territory" placeholder="Territory" value="{{ $user->route }}">
                            <datalist id="territories">
                                @foreach ($territories as $territory)
                                    <option value="{{ $territory }}">{{ $territory }}</option>
                                @endforeach
                            </datalist>
                            @if ($errors->has('territory'))
                                <span class="text-danger">{{ $errors->first('territory') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="mobile" class="col-md-4 col-form-label text-md-end text-start">Mobile</label>
                        <div class="col-md-6">
                          <input type="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="Mobile" value="{{ $user->mobile }}">
                            @if ($errors->has('mobile'))
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>
{{--
                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6">
                            <select class="form-select @error('roles') is-invalid @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                                @forelse ($roles as $role)
                                    @if ($role!='Super Admin')
                                    <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                    @else
                                        @if (Auth::user()->hasRole('Super Admin'))
                                        <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                        @endif
                                    @endif
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('roles'))
                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div> --}}

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6 border @error('roles') border-danger @enderror">
                            @forelse ($roles as $role)
                                @if ($role!='Super Admin' && $role!='Depo Incharge' && $role!='Manager' && $role!='Zonal Sales Executive' && $role!='Territory Sales Executive' && $role!='Delivery Man')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="roles[]" type="checkbox" role="switch" value="{{ $role }}" {{ in_array($role, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $role }}</label>
                                    </div>
                                    @else
                                        @if (
                                            Auth::user()->hasRole('Super Admin') ||
                                            (Auth::user()->hasRole('Manager') && ($role == 'Manager' || $role == 'Zonal Sales Executive' || $role == 'Territory Sales Executive')) ||
                                            (Auth::user()->hasRole('Zonal Sales Executive') && ($role == 'Zonal Sales Executive' || $role == 'Territory Sales Executive')) ||
                                            (Auth::user()->hasRole('Depo Incharge') && ($role == 'Depo Incharge' || $role == 'Delivery Man'))
                                        )
                                        @php
                                            $formattedRole = match ($role) {
                                                'Super Admin' => 'super-admin-role',
                                                'Manager' => 'manager-role',
                                                'Zonal Sales Executive' => 'zonal-sales-executive-role',
                                                'Territory Sales Executive' => 'territory-sales-executive-role',
                                                'Depo Incharge' => 'depo-incharge-role',
                                                'Delivery Man' => 'delivery-man-role',
                                                default => '',
                                            };
                                        @endphp
                                            <div class="form-check form-switch">
                                                <input id="{{ $formattedRole }}" class="form-check-input" name="roles[]" type="checkbox" role="switch" value="{{ $role }}" {{ in_array($role, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $role }}</label>
                                            </div>
                                        @endif
                                    @endif
                                @empty
                            @endforelse
                            @if ($errors->has('roles'))
                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Manager and Zonal Sales Executive selection (conditional) -->
                    <div class="mb-3 row" id="ManagerSelection" style="display: none;">
                        <label for="manager_id" class="col-md-4 col-form-label text-md-end text-start">Select Manager</label>
                        <div class="col-md-6">
                            <select class="form-control @error('manager_id') is-invalid @enderror" id="manager_id" name="manager_id">
                                <!-- Populate manager options dynamically -->
                                <option value="">Select Manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ $manager->id == $user->manager_id ? 'selected' : '' }}>{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('manager_id'))
                            <span class="text-danger">{{ $errors->first('manager_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 row" id="SalesManagerSelection" style="display: none;">
                        <label for="zse_id" class="col-md-4 col-form-label text-md-end text-start">Select Zonal Sales Executive</label>
                        <div class="col-md-6">
                            <select class="form-control @error('zse_id') is-invalid @enderror" id="zse_id" name="zse_id">
                                <!-- Populate Zonal Sales Executive options dynamically -->
                                <option value="">Select Zonal Sales Executive</option>
                                @foreach($salesManagers as $salesManager)
                                    <option value="{{ $salesManager->id }}" {{ $salesManager->id == $user->zse_id ? 'selected' : '' }}>{{ $salesManager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('zse_id'))
                            <span class="text-danger">{{ $errors->first('zse_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update User">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).ready(function () {
                // Ensure visibility on page load based on the old values
                if ($('#zonal-sales-executive-role').is(':checked') || $('#territory-sales-executive-role').is(':checked')) {
                    $('#ManagerSelection').show();
                }

                if ($('#territory-sales-executive-role').is(':checked')) {
                    $('#SalesManagerSelection').show();
                }

                $('#super-admin-role, #depo-incharge-role, #manager-role, #delivery-man-role, #territory-sales-executive-role, #zonal-sales-executive-role').on('change', function () {
                    // Uncheck all other checkboxes except the current one
                    $('#super-admin-role, #depo-incharge-role, #manager-role, #delivery-man-role, #territory-sales-executive-role, #zonal-sales-executive-role').not(this).prop('checked', false);

                    // Show or hide the Manager selection based on roles
                    if ($('#zonal-sales-executive-role').is(':checked') || $('#territory-sales-executive-role').is(':checked')) {
                        $('#ManagerSelection').show();
                    } else {
                        $('#ManagerSelection').hide();
                    }

                    // Show or hide the Zonal Sales Executive selection based on roles
                    if ($('#territory-sales-executive-role').is(':checked')) {
                        $('#SalesManagerSelection').show();
                    } else {
                        $('#SalesManagerSelection').hide();
                    }
                });

                $('#manager_id').on('change', function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var managerId = $(this).val();

                    if (managerId) {
                        $.ajax({
                            url: '{{ route('users.zonal-sales-executives') }}', // Ensure this route is defined in your routes file
                            data: { manager_id: managerId },
                            method: 'GET',
                            success: function (data) {
                                console.log(data); // Debugging to see the response data

                                // Clear existing options in zse_id dropdown
                                $('#zse_id').empty();

                                // Add a default option
                                $('#zse_id').append('<option value="">Select Zonal Sales Executive</option>');

                                // Populate new options dynamically
                                $.each(data, function (index, salesManager) {
                                    $('#zse_id').append('<option value="' + salesManager.id + '">' + salesManager.name + '</option>');
                                });
                            },
                            error: function (xhr) {
                                console.error('An error occurred:', xhr.responseText);
                            }
                        });
                    } else {
                        // Clear dropdown if no manager is selected
                        $('#zse_id').empty();
                        $('#zse_id').append('<option value="">Select Zonal Sales Executive</option>');
                    }
                });
            });
        });
    </script>
@endpush
</x-app-layout>
