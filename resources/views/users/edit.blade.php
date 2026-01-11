<!-- Edit User -->
<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <!-- Basic Info -->
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select select2 @error('employee_id') is-invalid @enderror" required id="employee_select_edit">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" data-name="{{ $employee->name }}" data-email="{{ $employee->email }}" {{ $user->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->employee_number }} - {{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">User Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="user_name_edit" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                        </div>
                        <input type="email" name="email" id="user_email_edit" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_edit_{{ $role->id }}"
                                        {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) || (!old() && $user->hasRole($role->name)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_edit_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group input-group-flat pass-group">
                            <input type="password" name="password" class="form-control pass-input @error('password') is-invalid @enderror" placeholder="Leave blank to keep current">
                            <span class="input-group-text toggle-password ">
                                <i class="ti ti-eye-off"></i>
                            </span>
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="small text-muted mt-1">Min 8 chars, mixed case, numbers, symbols.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Repeat Password</label>
                        <div class="input-group input-group-flat pass-group">
                            <input type="password" name="password_confirmation" class="form-control pass-input">
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
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#employee_select_edit').on('change', function() {
            var selected = $(this).find('option:selected');
            var name = selected.data('name');
            var email = selected.data('email');
            
            $('#user_name_edit').val(name);
            $('#user_email_edit').val(email);
        });
        
        if ($.fn.select2) {
            $('.select2').select2({
                dropdownParent: $('#offcanvas_edit')
            });
        }
    });
</script>
