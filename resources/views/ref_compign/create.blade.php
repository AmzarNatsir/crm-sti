@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Add New Campaign</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('ref-compign.index')}}">Campaigns</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <form action="{{route('ref-compign.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Campaign Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Revenue</label>
                                    <input type="text" class="form-control currency-format" name="target_revenue" value="{{old('target_revenue')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Sales (Count)</label>
                                    <input type="number" class="form-control" name="target_sales" value="{{old('target_sales')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Budget</label>
                                    <input type="text" class="form-control currency-format" name="badget" value="{{old('badget')}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Channel</label>
                                    <input type="text" class="form-control" name="channel" value="{{old('channel')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Segment</label>
                                    <input type="text" class="form-control" name="target_segment" value="{{old('target_segment')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" {{old('status') == 'active' ? 'selected' : ''}}>Active</option>
                                        <option value="inactive" {{old('status') == 'inactive' ? 'selected' : ''}}>Inactive</option>
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
                                    <input type="hidden" name="company_area_province_name" id="provinsi_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Regency</label>
                                    <select name="company_area_regency" id="kabupatenId" class="form-select select2" disabled>
                                        <option value="">Select Regency</option>
                                    </select>
                                    <input type="hidden" name="company_area_regency_name" id="kabupaten_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">District</label>
                                    <select name="company_area_district" id="kecamatanId" class="form-select select2" disabled>
                                        <option value="">Select District</option>
                                    </select>
                                    <input type="hidden" name="company_area_district_name" id="kecamatan_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Village</label>
                                    <select name="company_area_village" id="desaId" class="form-select select2" disabled>
                                        <option value="">Select Village</option>
                                    </select>
                                    <input type="hidden" name="company_area_village_name" id="desa_name">
                                </div>
                            </div>
                            
                            <!-- PIC -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Person In Charge (PIC)</label>
                                    <select name="pic_employee_id" class="form-select select2">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('pic_employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{old('description')}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="2">{{old('notes')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{route('ref-compign.index')}}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Campaign</button>
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
        // Currency Formatter
        $('.currency-format').on('input', function() {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Allow numbers and dot
            if (value) {
                // Split integer and decimal parts
                const parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Add commas
                
                // Reconstruct with limited decimal precision if needed, but basic comma separation for thousands:
                $(this).val(parts.join('.'));
            } else {
                $(this).val('');
            }
        });

        // Also simple version if we just want integers or standard localization
        // Let's stick to simple comma separation for millions as requested
        $('.currency-format').on('keyup', function() {
             let val = $(this).val();
             // Remove existing commas to get raw number
             val = val.replace(/,/g, '');
             if($.isNumeric(val)){
                 // Format with commas
                 let formatted = new Intl.NumberFormat('en-US').format(val);
                 $(this).val(formatted);
             }
        });
        
        // Better simple implementation for "2,000,000" style input
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

        // Load Provinces
        $.get("{{ route('api.provinces') }}", function(data) {
            let options = '<option value="">Select Province</option>';
            data.forEach(p => {
                options += `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`;
            });
            $('#provinsiId').html(options);
        });

        // Regional Cascades
        $('#provinsiId').change(function() {
            const name = $(this).val();
            const id = $(this).find(':selected').data('id');
            
            $('#kabupatenId').prop('disabled', true).html('<option>Loading...</option>');
            $('#kecamatanId, #desaId').prop('disabled', true).html('');

            if(id) {
                $.get(`/api/provinces/${id}/regencies`, function(data) {
                    let options = '<option value="">Select Regency</option>';
                    data.forEach(r => options += `<option value="${r.name}" data-id="${r.id}">${r.name}</option>`);
                    $('#kabupatenId').prop('disabled', false).html(options);
                });
            }
        });

        $('#kabupatenId').change(function() {
            const name = $(this).val();
            const id = $(this).find(':selected').data('id');

            $('#kecamatanId').prop('disabled', true).html('<option>Loading...</option>');
            $('#desaId').prop('disabled', true).html('');

            if(id) {
                $.get(`/api/regencies/${id}/districts`, function(data) {
                    let options = '<option value="">Select District</option>';
                    data.forEach(d => options += `<option value="${d.name}" data-id="${d.id}">${d.name}</option>`);
                    $('#kecamatanId').prop('disabled', false).html(options);
                });
            }
        });

        $('#kecamatanId').change(function() {
            const name = $(this).val();
            const id = $(this).find(':selected').data('id');

            $('#desaId').prop('disabled', true).html('<option>Loading...</option>');

            if(id) {
                $.get(`/api/districts/${id}/villages`, function(data) {
                    let options = '<option value="">Select Village</option>';
                    data.forEach(v => options += `<option value="${v.name}" data-id="${v.id}">${v.name}</option>`);
                    $('#desaId').prop('disabled', false).html(options);
                });
            }
        });
    });
</script>
@endpush
