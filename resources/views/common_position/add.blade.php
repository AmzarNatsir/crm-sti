<!-- Add Position -->
<form action="{{ route('common-position.store') }}" method="POST">
    @csrf
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Position Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
