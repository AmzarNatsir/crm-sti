<!-- Edit Type -->
<form action="{{ route('common-type.update', $type->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Type Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="{{ $type->name }}" required>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
