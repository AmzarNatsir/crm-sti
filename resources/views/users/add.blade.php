<!-- Add User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Add New User</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div>
                    <!-- Basic Info -->
                    <div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" class="form-select select2 @error('employee_id') is-invalid @enderror" required id="employee_select">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" data-name="{{ $employee->name }}" data-email="{{ $employee->email }}">{{ $employee->employee_number }} - {{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">User Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="user_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                    </div>
                                    <input type="email" name="email" id="user_email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                     @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    @foreach($roles as $role)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                        {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                    @endforeach
                                    @error('roles')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-flat pass-group">
                                        <input type="password" name="password" class="form-control pass-input @error('password') is-invalid @enderror" placeholder="Min 8 chars, mixed case, numbers, symbols">
                                        <span class="input-group-text toggle-password ">
                                            <i class="ti ti-eye-off"></i>
                                        </span>
                                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="small text-muted mt-1">Min 8 chars, upper/lower case, numbers, and symbols.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Repeat Password <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-flat pass-group">
                                        <input type="password" name="password_confirmation" class="form-control pass-input" required>
                                        <span class="input-group-text toggle-password ">
                                            <i class="ti ti-eye-off"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Basic Info -->
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /Add User -->

    <script>
        $(document).ready(function() {
            $('#employee_select').on('change', function() {
                var selected = $(this).find('option:selected');
                var name = selected.data('name');
                var email = selected.data('email');

                $('#user_name').val(name);
                $('#user_email').val(email);
            });

            // Re-initialize Select2 if needed (it should be handled by the main view or layout)
            if ($.fn.select2) {
                $('.select2').select2({
                    dropdownParent: $('#offcanvas_add')
                });
            }
        });
    </script>
