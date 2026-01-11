<!-- Add User -->
<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div>
        <!-- Basic Info -->
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-0">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Module</th>
                                    <th class="text-center">View</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Edit/Update</th>
                                    <th class="text-center">Delete</th>
                                    <th class="text-center">Manage</th>
                                    <th class="text-center">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedPermissions as $module => $actions)
                                <tr>
                                    <td class="fw-bold">{{ $module }}</td>
                                    @php
                                        $availableActions = [
                                            'view', 
                                            'create', 
                                            ['edit', 'update'], 
                                            'delete', 
                                            'manage'
                                        ];
                                    @endphp
                                    @foreach($availableActions as $action)
                                    <td class="text-center">
                                        @php
                                            $permission = null;
                                            if (is_array($action)) {
                                                foreach($action as $act) {
                                                    if(isset($actions[$act])) {
                                                        $permission = $actions[$act];
                                                        break;
                                                    }
                                                }
                                            } else {
                                                $permission = $actions[$action] ?? null;
                                            }
                                        @endphp
                                        @if($permission)
                                        <div class="form-check form-check-md d-inline-block">
                                            <input class="form-check-input perm-check-{{ Str::slug($module) }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                                        </div>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    @endforeach
                                    <td class="text-center">
                                        <div class="form-check form-check-md d-inline-block">
                                            <input class="form-check-input select-all-module" type="checkbox" data-module="{{ Str::slug($module) }}">
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Basic Info -->
    </div>
    <div class="d-flex align-items-center justify-content-end mt-4">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Role</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('.select-all-module').on('change', function() {
            const module = $(this).data('module');
            $(`.perm-check-${module}`).prop('checked', $(this).is(':checked'));
        });
    });
</script>
<!-- /Add Role -->
