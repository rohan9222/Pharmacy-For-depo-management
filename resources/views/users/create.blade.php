<x-app-layout>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New User
                </div>
                <div class="float-end">
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                          <input type="name" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="address" class="col-md-4 col-form-label text-md-end text-start">Address</label>
                        <div class="col-md-6">
                          <input type="address" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="mobile" class="col-md-4 col-form-label text-md-end text-start">Mobile</label>
                        <div class="col-md-6">
                          <input type="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="Mobile" value="{{ old('mobile') }}">
                            @if ($errors->has('mobile'))
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="mb-3 row">
                        <label for="balance" class="col-md-4 col-form-label text-md-end text-start">balance</label>
                        <div class="col-md-6">
                          <input type="balance" class="form-control @error('balance') is-invalid @enderror" id="balance" name="balance" placeholder="balance" value="{{ old('balance') }}">
                            @if ($errors->has('balance'))
                                <span class="text-danger">{{ $errors->first('balance') }}</span>
                            @endif
                        </div>
                    </div> --}}

                    <div class="mb-3 row">
                        {{-- <label for="product_target" class="col-md-4 col-form-label text-md-end text-start">Product Target</label>
                        <div class="col-md-6">
                          <input type="product_target" class="form-control @error('product_target') is-invalid @enderror" id="product_target" name="product_target" placeholder="Product Target" value="{{ old('product_target') }}">
                            @if ($errors->has('product_target'))
                                <span class="text-danger">{{ $errors->first('product_target') }}</span>
                            @endif
                        </div> --}}
                        <label for="sales_target" class="col-md-4 col-form-label text-md-end text-start">Sales Target</label>
                        <div class="col-md-6">
                          <input type="sales_target" class="form-control @error('sales_target') is-invalid @enderror" id="sales_target" name="sales_target" placeholder="Sales Target" value="{{ old('sales_target') }}">
                            @if ($errors->has('sales_target'))
                                <span class="text-danger">{{ $errors->first('sales_target') }}</span>
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

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6 border @error('roles') border-danger @enderror">
                            @forelse ($roles as $role)
                                @if ($role!='Super Admin' && $role!='Depo Incharge' && $role!='Manager' && $role!='Sales Manager' && $role!='Field Officer' && $role!='Delivery Man')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="roles[]" type="checkbox" role="switch" value="{{ $role }}" {{ in_array($role, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $role }}</label>
                                    </div>
                                    @else
                                        @if (
                                            Auth::user()->hasRole('Super Admin') ||
                                            (Auth::user()->hasRole('Manager') && ( $role == 'Sales Manager' || $role == 'Field Officer')) ||
                                            (Auth::user()->hasRole('Sales Manager') && ( $role == 'Field Officer')) ||
                                            (Auth::user()->hasRole('Depo Incharge') && ($role == 'Manager' || $role == 'Sales Manager' || $role == 'Field Officer' || $role == 'Delivery Man'))
                                        )
                                        @php
                                            $formattedRole = match ($role) {
                                                'Super Admin' => 'super-admin-role',
                                                'Manager' => 'manager-role',
                                                'Sales Manager' => 'sales-manager-role',
                                                'Field Officer' => 'field-officer-role',
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

                    <!-- Manager and Sales Manager selection (conditional) -->
                    <div class="mb-3 row" id="ManagerSelection" style="display: none;">
                        <label for="manager_id" class="col-md-4 col-form-label text-md-end text-start">Select Manager</label>
                        <div class="col-md-6">
                            <select class="form-control @error('manager_id') is-invalid @enderror" id="manager_id" name="manager_id">
                                <!-- Populate manager options dynamically -->
                                <option value="">Select Manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('manager_id'))
                            <span class="text-danger">{{ $errors->first('manager_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 row" id="SalesManagerSelection" style="display: none;">
                        <label for="sales_manager_id" class="col-md-4 col-form-label text-md-end text-start">Select Sales Manager</label>
                        <div class="col-md-6">
                            <select class="form-control @error('sales_manager_id') is-invalid @enderror" id="sales_manager_id" name="sales_manager_id">
                                <!-- Populate sales manager options dynamically -->
                                <option value="">Select Sales Manager</option>
                                @foreach($salesManagers as $salesManager)
                                    <option value="{{ $salesManager->id }}">{{ $salesManager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('sales_manager_id'))
                            <span class="text-danger">{{ $errors->first('sales_manager_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add User">
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
                if ($('#sales-manager-role').is(':checked') || $('#field-officer-role').is(':checked')) {
                    $('#ManagerSelection').show();
                }

                if ($('#field-officer-role').is(':checked')) {
                    $('#SalesManagerSelection').show();
                }

                $('#super-admin-role, #depo-incharge-role, #manager-role, #delivery-man-role, #field-officer-role, #sales-manager-role').on('change', function () {
                    // Uncheck all other checkboxes except the current one
                    $('#super-admin-role, #depo-incharge-role, #manager-role, #delivery-man-role, #field-officer-role, #sales-manager-role').not(this).prop('checked', false);

                    // Show or hide the Manager selection based on roles
                    if ($('#sales-manager-role').is(':checked') || $('#field-officer-role').is(':checked')) {
                        $('#ManagerSelection').show();
                    } else {
                        $('#ManagerSelection').hide();
                    }

                    // Show or hide the Sales Manager selection based on roles
                    if ($('#field-officer-role').is(':checked')) {
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
                            url: '{{ route('users.sales-managers') }}', // Ensure this route is defined in your routes file
                            data: { manager_id: managerId },
                            method: 'GET',
                            success: function (data) {
                                console.log(data); // Debugging to see the response data

                                // Clear existing options in sales_manager_id dropdown
                                $('#sales_manager_id').empty();

                                // Add a default option
                                $('#sales_manager_id').append('<option value="">Select Sales Manager</option>');

                                // Populate new options dynamically
                                $.each(data, function (index, salesManager) {
                                    $('#sales_manager_id').append('<option value="' + salesManager.id + '">' + salesManager.name + '</option>');
                                });
                            },
                            error: function (xhr) {
                                console.error('An error occurred:', xhr.responseText);
                            }
                        });
                    } else {
                        // Clear dropdown if no manager is selected
                        $('#sales_manager_id').empty();
                        $('#sales_manager_id').append('<option value="">Select Sales Manager</option>');
                    }
                });
            });
        });
    </script>
@endpush
</x-app-layout>
