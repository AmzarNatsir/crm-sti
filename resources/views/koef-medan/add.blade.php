<form action="{{ route('koef-medan.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="kode_medan" maxlength="10" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="description" maxlength="100" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">KM <span class="text-danger">*</span></label>
                <input type="text" class="form-control input-decimal" name="km" maxlength="5" placeholder="e.g. 1.15" required>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-4">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>

<script>
    $(document).off('input', '.input-decimal').on('input', '.input-decimal', function(e) {
        var value = $(this).val().replace(/[^0-9.]/g, '');
        // Prevent multiple dots
        if ((value.match(/\./g) || []).length > 1) {
            value = value.replace(/\.+$/, "");
        }
        $(this).val(value);
    });

    $(document).off('keydown', '.input-decimal').on('keydown', '.input-decimal', function(e) {
        var allowedCodes = [46, 8, 9, 27, 13, 110, 190];
        if ($.inArray(e.keyCode, allowedCodes) !== -1 ||
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>
