<!-- Add Customer -->
<form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select class="form-control" name="type" required>
                    <option value="">Select Type</option>
                    <option value="lead">Lead</option>
                    <option value="prospect">Prospect</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Commodity <span class="text-danger">*</span></label>
                <select class="form-control" name="commodity_id" required>
                    <option value="">Select Commodity</option>
                    @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}">{{ $commodity->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Identity No (KTP)</label>
                <input type="text" class="form-control" name="identity_no">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="date_of_birth">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="company_name" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="phone" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="address" required>
            </div>
        </div>

        <!-- New location fields -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Province</label>
                <select class="form-control select2" name="province_code" id="province_code">
                    <option value="">Select Province</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" data-name="{{ $province->name }}">{{ $province->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="province" id="province_name">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">District / Kabupaten (Regency)</label>
                <select class="form-control select2" name="district_code" id="district_code" disabled>
                    <option value="">Select District / Kabupaten</option>
                </select>
                <input type="hidden" name="district" id="district_name">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Sub-District / Kecamatan (District)</label>
                <select class="form-control select2" name="sub_district_code" id="sub_district_code" disabled>
                    <option value="">Select Sub-District / Kecamatan</option>
                </select>
                <input type="hidden" name="sub_district" id="sub_district_name">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Village / Desa</label>
                <select class="form-control select2" name="village_code" id="village_code" disabled>
                    <option value="">Select Village / Desa</option>
                </select>
                <input type="hidden" name="village" id="village_name">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Point Coordinate</label>
                <input type="text" class="form-control" name="point_coordinate">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Photo Profile</label>
                <input type="file" class="form-control" name="photo_profile" accept="image/*" onchange="previewImage(this, 'customer-photo-preview')">
                <div class="mt-2 text-center">
                    <img id="customer-photo-preview" src="#" alt="Preview" style="display:none; max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            // Initialize Select2
            $('.select2').select2({
                dropdownParent: $('#offcanvas_add')
            });

            const $provinceSelect = $('#province_code');
            const $regencySelect = $('#district_code');
            const $districtSelect = $('#sub_district_code');
            const $villageSelect = $('#village_code');

            // Hidden name inputs
            const provinceName = document.getElementById('province_name');
            const regencyName = document.getElementById('district_name');
            const districtName = document.getElementById('sub_district_name');
            const villageName = document.getElementById('village_name');

            if ($provinceSelect.length === 0) return;

            // Update Name inputs on change
            function updateName(select, input) {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.name) {
                    input.value = selectedOption.dataset.name;
                } else {
                    input.value = '';
                }
            }

            $provinceSelect.on('change', function() {
                updateName(this, provinceName);
                const provinceId = this.value;
                
                // Reset child dropdowns
                $regencySelect.html('<option value="">Select District / Kabupaten</option>').prop('disabled', true).trigger('change.select2');
                $districtSelect.html('<option value="">Select Sub-District / Kecamatan</option>').prop('disabled', true).trigger('change.select2');
                $villageSelect.html('<option value="">Select Village / Desa</option>').prop('disabled', true).trigger('change.select2');

                if (provinceId) {
                    fetch(`/api/provinces/${provinceId}/regencies`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(item => {
                                // Create generic Option to work with Select2
                                const option = new Option(item.name, item.id, false, false);
                                option.dataset.name = item.name;
                                $regencySelect.append(option);
                            });
                            $regencySelect.prop('disabled', false).trigger('change.select2');
                        });
                }
            });

            $regencySelect.on('change', function() {
                updateName(this, regencyName);
                const regencyId = this.value;
                
                $districtSelect.html('<option value="">Select Sub-District / Kecamatan</option>').prop('disabled', true).trigger('change.select2');
                $villageSelect.html('<option value="">Select Village / Desa</option>').prop('disabled', true).trigger('change.select2');

                if (regencyId) {
                    fetch(`/api/regencies/${regencyId}/districts`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(item => {
                                const option = new Option(item.name, item.id, false, false);
                                option.dataset.name = item.name;
                                $districtSelect.append(option);
                            });
                            $districtSelect.prop('disabled', false).trigger('change.select2');
                        });
                }
            });

            $districtSelect.on('change', function() {
                updateName(this, districtName);
                const districtId = this.value;
                
                $villageSelect.html('<option value="">Select Village / Desa</option>').prop('disabled', true).trigger('change.select2');

                if (districtId) {
                    fetch(`/api/districts/${districtId}/villages`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(item => {
                                const option = new Option(item.name, item.id, false, false);
                                option.dataset.name = item.name;
                                $villageSelect.append(option);
                            });
                            $villageSelect.prop('disabled', false).trigger('change.select2');
                        });
                }
            });

            $villageSelect.on('change', function() {
                updateName(this, villageName);
            });
        })();

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
    <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
