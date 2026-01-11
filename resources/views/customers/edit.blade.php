<!-- Edit Customer -->
<form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select class="form-control" name="type" required>
                    <option value="">Select Type</option>
                    <option value="lead" {{ $customer->type == 'lead' ? 'selected' : '' }}>Lead</option>
                    <option value="prospect" {{ $customer->type == 'prospect' ? 'selected' : '' }}>Prospect</option>
                    <option value="customer" {{ $customer->type == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Commodity <span class="text-danger">*</span></label>
                <select class="form-control" name="commodity_id" required>
                    <option value="">Select Commodity</option>
                    @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}" {{ $customer->commodity_id == $commodity->id ? 'selected' : '' }}>{{ $commodity->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Identity No (KTP)</label>
                <input type="text" class="form-control" name="identity_no" value="{{ $customer->identity_no }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="date_of_birth" value="{{ $customer->date_of_birth }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="company_name" value="{{ $customer->company_name }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="phone" value="{{ $customer->phone }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" value="{{ $customer->email }}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="address" value="{{ $customer->address }}" required>
            </div>
        </div>

        <!-- New location fields -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Province</label>
                <select class="form-control select2" name="province_code" id="province_code_edit">
                    <option value="">Select Province</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" data-name="{{ $province->name }}" {{ $customer->province_code == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="province" id="province_name_edit" value="{{ $customer->province }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">District / Kabupaten (Regency)</label>
                <select class="form-control select2" name="district_code" id="district_code_edit" {{ $customer->district_code ? '' : 'disabled' }}>
                    <option value="">Select District / Kabupaten</option>
                    @foreach($regencies as $regency)
                        <option value="{{ $regency->id }}" data-name="{{ $regency->name }}" {{ $customer->district_code == $regency->id ? 'selected' : '' }}>{{ $regency->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="district" id="district_name_edit" value="{{ $customer->district }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Sub-District / Kecamatan (District)</label>
                <select class="form-control select2" name="sub_district_code" id="sub_district_code_edit" {{ $customer->sub_district_code ? '' : 'disabled' }}>
                    <option value="">Select Sub-District / Kecamatan</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" data-name="{{ $district->name }}" {{ $customer->sub_district_code == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="sub_district" id="sub_district_name_edit" value="{{ $customer->sub_district }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Village / Desa</label>
                <select class="form-control select2" name="village_code" id="village_code_edit" {{ $customer->village_code ? '' : 'disabled' }}>
                    <option value="">Select Village / Desa</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->id }}" data-name="{{ $village->name }}" {{ $customer->village_code == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="village" id="village_name_edit" value="{{ $customer->village }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Point Coordinate</label>
                <input type="text" class="form-control" name="point_coordinate" value="{{ $customer->point_coordinate }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Photo Profile</label>
                <input type="file" class="form-control" name="photo_profile" accept="image/*" onchange="previewImage(this, 'customer-photo-edit-preview')">
                <div class="mt-2 text-center">
                    @if($customer->photo_profile)
                        <img id="customer-photo-edit-preview" src="{{ asset('storage/' . $customer->photo_profile) }}" alt="Profile" style="max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                    @else
                        <img id="customer-photo-edit-preview" src="#" alt="Preview" style="display:none; max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                    @endif
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

            const $provinceSelect = $('#province_code_edit');
            const $regencySelect = $('#district_code_edit');
            const $districtSelect = $('#sub_district_code_edit');
            const $villageSelect = $('#village_code_edit');

            // Hidden name inputs
            const provinceName = document.getElementById('province_name_edit');
            const regencyName = document.getElementById('district_name_edit');
            const districtName = document.getElementById('sub_district_name_edit');
            const villageName = document.getElementById('village_name_edit');

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
                
                // Clear and disable child dropdowns
                $regencySelect.html('<option value="">Select District / Kabupaten</option>').prop('disabled', true).trigger('change.select2');
                $districtSelect.html('<option value="">Select Sub-District / Kecamatan</option>').prop('disabled', true).trigger('change.select2');
                $villageSelect.html('<option value="">Select Village / Desa</option>').prop('disabled', true).trigger('change.select2');

                if (provinceId) {
                    fetch(`/api/provinces/${provinceId}/regencies`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(item => {
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

        if (typeof previewImage !== 'function') {
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    </script>
    <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
