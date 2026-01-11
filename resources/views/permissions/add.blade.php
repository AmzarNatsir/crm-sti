<form action="{{ route('permissions.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Permission Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="e.g. users-create" required>
                <small class="text-muted">Use module-action format (e.g., users-create, users-edit)</small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Guard Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="guard_name" value="web" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Subject/Menu <span class="text-danger">*</span></label>
                <select class="form-select" name="subject_id" required>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label d-block">Assign to Roles</label>
                <div class="row">
                    @foreach($roles as $role)
                    <div class="col-md-4">
                        <div class="form-check form-check-md mb-2">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}">
                            <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-4">
        <button type="button" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Permission</button>
    </div>
</form>
