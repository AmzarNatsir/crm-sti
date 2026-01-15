@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Edit Campaign</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('ref-compign.index')}}">Campaigns</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <form action="{{route('ref-compign.update', $compign->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Campaign Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{old('name', $compign->name)}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" value="{{old('start_date', $compign->start_date)}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" value="{{old('end_date', $compign->end_date)}}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Revenue</label>
                                    <input type="text" class="form-control currency-format" name="target_revenue" value="{{old('target_revenue', number_format($compign->target_revenue, 0, '.', ','))}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Sales (Count)</label>
                                    <input type="number" class="form-control" name="target_sales" value="{{old('target_sales', $compign->target_sales)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Budget</label>
                                    <input type="text" class="form-control currency-format" name="badget" value="{{old('badget', number_format($compign->badget, 0, '.', ','))}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Channel</label>
                                    <input type="text" class="form-control" name="channel" value="{{old('channel', $compign->channel)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Segment</label>
                                    <input type="text" class="form-control" name="target_segment" value="{{old('target_segment', $compign->target_segment)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" {{old('status', $compign->status) == 'active' ? 'selected' : ''}}>Active</option>
                                        <option value="inactive" {{old('status', $compign->status) == 'inactive' ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Regional Data -->
                            <div class="col-md-12">
                                <h5 class="mb-3 mt-3 text-primary">Company Area</h5>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Province</label>
                                    <select name="company_area_province" id="provinsiId" class="form-select select2">
                                        <option value="">Select Province</option>
                                    </select>
                                    <input type="hidden" name="company_area_province_name" id="provinsi_name" value="{{ $compign->company_area_province }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Regency</label>
                                    <select name="company_area_regency" id="kabupatenId" class="form-select select2">
                                        <option value="">Select Regency</option>
                                    </select>
                                    <input type="hidden" name="company_area_regency_name" id="kabupaten_name" value="{{ $compign->company_area_regency }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">District</label>
                                    <select name="company_area_district" id="kecamatanId" class="form-select select2">
                                        <option value="">Select District</option>
                                    </select>
                                    <input type="hidden" name="company_area_district_name" id="kecamatan_name" value="{{ $compign->company_area_district }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Village</label>
                                    <select name="company_area_village" id="desaId" class="form-select select2">
                                        <option value="">Select Village</option>
                                    </select>
                                    <input type="hidden" name="company_area_village_name" id="desa_name" value="{{ $compign->company_area_village }}">
                                </div>
                            </div>
                            
                            <!-- PIC -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Person In Charge (PIC)</label>
                                    <select name="pic_employee_id" class="form-select select2">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('pic_employee_id', $compign->pic_employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{old('description', $compign->description)}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="2">{{old('notes', $compign->notes)}}</textarea>
                                </div>
                            </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{route('ref-compign.index')}}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Campaign</button>
                    </div>
                </form>
            </div>

        </div>

        @component('components.footer')
        @endcomponent

    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Currency Formatter for "2,000,000" style
        $(document).on('input', '.currency-format', function(){
             let val = $(this).val().replace(/[^0-9]/g, '');
             if(val) {
                 $(this).val(new Intl.NumberFormat('en-US').format(val));
             } else {
                 $(this).val('');
             }
        });

        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        // Pre-defined values
        const initialProvinceName = "{{ $compign->company_area_province }}";
        const initialRegencyName = "{{ $compign->company_area_regency }}";
        const initialDistrictName = "{{ $compign->company_area_district }}";
        const initialVillageName = "{{ $compign->company_area_village }}";

        let selectedProvinceId = null;
        let selectedRegencyId = null;
        let selectedDistrictId = null;

        // Load Provinces
        $.get("{{ route('api.provinces') }}", function(data) {
            let options = '<option value="">Select Province</option>';
            data.forEach(p => {
                const selected = p.name === initialProvinceName ? 'selected' : '';
                if(selected) selectedProvinceId = p.id;
                options += `<option value="${p.name}" data-id="${p.id}" ${selected}>${p.name}</option>`;
            });
            $('#provinsiId').html(options);

            // Trigger cascades if value exists
            if (selectedProvinceId) {
                loadRegencies(selectedProvinceId, initialRegencyName);
            }
        });

        function loadRegencies(provinceId, selectedName = null) {
             $.get(`/api/provinces/${provinceId}/regencies`, function(data) {
                let options = '<option value="">Select Regency</option>';
                data.forEach(r => {
                    const selected = r.name === selectedName ? 'selected' : '';
                    if(selected) selectedRegencyId = r.id;
                    options += `<option value="${r.name}" data-id="${r.id}" ${selected}>${r.name}</option>`;
                });
                $('#kabupatenId').html(options);

                if (selectedRegencyId && initialDistrictName) {
                    loadDistricts(selectedRegencyId, initialDistrictName);
                }
            });
        }

        function loadDistricts(regencyId, selectedName = null) {
            $.get(`/api/regencies/${regencyId}/districts`, function(data) {
                let options = '<option value="">Select District</option>';
                data.forEach(d => {
                    const selected = d.name === selectedName ? 'selected' : '';
                    if(selected) selectedDistrictId = d.id;
                    options += `<option value="${d.name}" data-id="${d.id}" ${selected}>${d.name}</option>`;
                });
                $('#kecamatanId').html(options);

                if (selectedDistrictId && initialVillageName) {
                    loadVillages(selectedDistrictId, initialVillageName);
                }
            });
        }

        function loadVillages(districtId, selectedName = null) {
            $.get(`/api/districts/${districtId}/villages`, function(data) {
                let options = '<option value="">Select Village</option>';
                data.forEach(v => {
                    const selected = v.name === selectedName ? 'selected' : '';
                    options += `<option value="${v.name}" data-id="${v.id}" ${selected}>${v.name}</option>`;
                });
                $('#desaId').html(options);
            });
        }

        // Change Handlers
        $('#provinsiId').change(function() {
            const id = $(this).find(':selected').data('id');
            $('#kabupatenId').html('<option>Loading...</option>');
            $('#kecamatanId, #desaId').html('<option value="">Select</option>');
            
            if(id) {
                loadRegencies(id);
            }
        });

        $('#kabupatenId').change(function() {
            const id = $(this).find(':selected').data('id');
            $('#kecamatanId').html('<option>Loading...</option>');
            $('#desaId').html('<option value="">Select</option>');
            
            if(id) {
                loadDistricts(id);
            }
        });

        $('#kecamatanId').change(function() {
            const id = $(this).find(':selected').data('id');
            $('#desaId').html('<option>Loading...</option>');
            
            if(id) {
                 loadVillages(id);
            }
        });
    });
</script>
@endpush
