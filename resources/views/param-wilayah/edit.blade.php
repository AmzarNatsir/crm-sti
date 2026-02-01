<form action="{{ route('param-wilayah.update', $paramWilayah->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Zona <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="zona" value="{{ $paramWilayah->zona }}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Province <span class="text-danger">*</span></label>
                <select class="form-select select2" name="province_id" required>
                    <option value="">Select Province</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ $paramWilayah->province_id == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">CKM</label>
                <input type="text" class="form-control input-number" name="ckm" value="{{ $paramWilayah->ckm ? number_format($paramWilayah->ckm, 0, '.', ',') : '' }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">CT</label>
                <input type="text" class="form-control input-number" name="ct" value="{{ $paramWilayah->ct ? number_format($paramWilayah->ct, 0, '.', ',') : '' }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Tarif Min</label>
                <input type="text" class="form-control input-number" name="tarif_min" value="{{ $paramWilayah->tarif_min ? number_format($paramWilayah->tarif_min, 0, '.', ',') : '' }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Alpha Max Retail (Decimal)</label>
                <input type="text" class="form-control input-decimal" name="alpha_max_retail" value="{{ $paramWilayah->alpha_max_retail }}" maxlength="5" placeholder="e.g. 0.07">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Alpha Max Reseller (Decimal)</label>
                <input type="text" class="form-control input-decimal" name="alpha_max_reseller" value="{{ $paramWilayah->alpha_max_reseller }}" maxlength="5" placeholder="e.g. 0.07">
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-4">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

<script>
    if ($('.select2').length > 0) {
        $('.select2').select2({
            dropdownParent: $('#offcanvas_add')
        });
    }

    $(document).off('input', '.input-number').on('input', '.input-number', function(e) {
        var value = $(this).val().replace(/[^0-9]/g, '');
        if (value !== '') {
            $(this).val(new Intl.NumberFormat('en-US').format(value));
        } else {
            $(this).val('');
        }
    });

    $(document).off('input', '.input-decimal').on('input', '.input-decimal', function(e) {
        var value = $(this).val().replace(/[^0-9.]/g, '');
        // Prevent multiple dots
        if ((value.match(/\./g) || []).length > 1) {
            value = value.replace(/\.+$/, "");
        }
        $(this).val(value);
    });

    $(document).off('keydown', '.input-number, .input-decimal').on('keydown', '.input-number, .input-decimal', function(e) {
        // Allow: backspace, delete, tab, escape, enter and . (110, 190)
        var allowedCodes = [46, 8, 9, 27, 13, 110, 190];
        if ($.inArray(e.keyCode, allowedCodes) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>
