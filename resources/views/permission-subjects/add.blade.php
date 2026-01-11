<form action="{{ route('permission-subjects.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required placeholder="e.g., Customers, Users">
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-4">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Subject</button>
    </div>
</form>
