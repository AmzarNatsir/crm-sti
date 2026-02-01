<?php $page = 'index'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-0">Home</h4>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <div class="daterangepick form-control w-auto d-flex align-items-center">
                        <i class="ti ti-calendar text-dark me-2"></i>
                        <span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Start Welcome Wrap -->
            <div class="welcome-wrap mb-4">
                <div class=" d-flex align-items-center justify-content-between flex-wrap gap-3 bg-dark rounded p-4">
                    <div class="">
                        <h2 class="mb-1 text-white fs-24">Welcome Back, {{ auth()->user()->name }}</h2>
                        <p class="text-light fs-14 mb-0">Welcome to CRM PTTani</p>
                    </div>
                    <!-- <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{url('home')}}" class="btn btn-danger btn-sm">Companies</a>
                        <a href="{{url('home')}}" class="btn btn-light btn-sm">All Packages</a>
                    </div> -->
                </div>
            </div>
            <!-- Endc Welcome Wrap -->

            <!-- Shipping Simulator Start -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title text-primary mb-0"><i class="ti ti-calculator me-2"></i>PERHITUNGAN ONGKIR SEJATI</h5>
                </div>
                <div class="card-body">
                    <form id="shipping-simulator-form">
                        @csrf
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6 border-end">
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Provinsi</label>
                                    <div class="col-sm-8">
                                        <select class="form-select select2" name="province_id" id="province_id" required>
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Kabupaten/Kota</label>
                                    <div class="col-sm-8">
                                        <select class="form-select select2" name="regency_id" id="regency_id" disabled>
                                            <option value="">Select Regency</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Kecamatan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select select2" name="district_id" id="district_id" disabled>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Desa (Kode - Nama)</label>
                                    <div class="col-sm-8">
                                        <select class="form-select select2" name="village_id" id="village_id" disabled>
                                            <option value="">Select Village</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Jenis Armada</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" name="jenis_armada" id="jenis_armada">
                                            <option value="">Select Armada</option>
                                            <option value="Pickup">Pickup</option>
                                            <option value="Engkel">Engkel</option>
                                            <option value="Truk_6_Roda">Truk 6 Roda</option>
                                            <option value="Tronton">Tronton</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">W (Muatan ton)</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="0.01" class="form-control" name="w" id="input_w">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Cap (Kapasitas ton)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control-plaintext fw-bold" name="cap" id="val_cap" readonly value="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Tipe Pelanggan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" name="customer_type" id="customer_type">
                                            <option value="Retail">Retail</option>
                                            <option value="Reseller">Reseller</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Kr/Kw/Kp (Risk/Weather/Ferry)</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="kr" id="input_kr" placeholder="Kr">
                                            <input type="number" class="form-control" name="kw" id="input_kw" placeholder="Kw">
                                            <input type="number" class="form-control" name="kp" id="input_kp" placeholder="Kp">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Nilai Faktur (Rp)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control input-format-number" name="nilai_faktur_formatted" id="input_nilai_faktur">
                                        <input type="hidden" name="nilai_faktur" id="val_nilai_faktur">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Subsidi% (opsional)</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="0.1" class="form-control" name="subsidi" id="input_subsidi">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Koef Medan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" name="kode_medan" id="kode_medan">
                                            <option value="">Default</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Jarak (D) & Waktu (T)</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="d" id="input_d" placeholder="Jarak (D)">
                                            <input type="number" class="form-control" name="t" id="input_t" placeholder="Waktu (T)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6 ps-lg-4">
                                <div class="row mb-2">
                                    <label class="col-sm-5 text-muted">Ckm_z</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_ckm_z">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 text-muted">Ct_z</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_ct_z">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 text-muted">Tarif Min</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_tarif_min">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 text-muted">Kode_Desa</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_kode_desa">-</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 text-muted">Alpha_Max</label>
                                    <div class="col-sm-7 text-end fw-bold text-info" id="res_alpha_max">0</div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-sm-5 text-muted">Ongkir_Maks</label>
                                    <div class="col-sm-7 text-end fw-bold text-danger" id="res_ongkir_maks">0</div>
                                </div>

                                <div class="row mb-2 border-top pt-3">
                                    <label class="col-sm-5 text-muted">Km (medan)</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_km_medan">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 text-muted">Karm</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_karm">0</div>
                                </div>
                                <div class="row mb-4 border-bottom pb-3">
                                    <label class="col-sm-5 text-muted">Fm (W/Cap)</label>
                                    <div class="col-sm-7 text-end fw-bold" id="res_fm">0</div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-sm-5 h6 mb-0">ONGKIR_RIIL</label>
                                    <div class="col-sm-7 text-end h6 mb-0" id="res_ongkir_riil">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 h6 mb-0">ONGKIR_TAGIH (ceiling)</label>
                                    <div class="col-sm-7 text-end h6 mb-0" id="res_ongkir_tagih">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 h6 mb-0 text-primary">SETELAH SUBSIDI</label>
                                    <div class="col-sm-7 text-end h6 mb-0 text-primary" id="res_setelah_subsidi">0</div>
                                </div>
                                <div class="row mb-2">
                                    <label class="col-sm-5 h4 mb-0 text-success">ONGKIR_FINAL</label>
                                    <div class="col-sm-7 text-end h4 mb-0 text-success" id="res_ongkir_final">0</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Shipping Simulator End -->

        </div>
        <!-- End Content -->

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select...'
        });

        // Capacity mapping
        const armadaCapMap = {
            'Pickup': 1.5,
            'Engkel': 2,
            'Truk_6_Roda': 5,
            'Tronton': 15
        };

        // Fetch initial data
        $.get("{{ route('api.provinces') }}", function(data) {
            let options = '<option value="">Select Province</option>';
            data.forEach(item => { options += `<option value="${item.id}">${item.name}</option>`; });
            $('#province_id').html(options).trigger('change');
        });

        // Cascading logic
        $('#province_id').on('change', function() {
            const id = $(this).val();
            
            // Reset and disable children
            $('#regency_id, #district_id, #village_id').html('<option value="">Select...</option>').prop('disabled', true).trigger('change');
            
            if (id) {
                $.get("{{ url('api/provinces') }}/" + id + "/regencies", function(data) {
                    let options = '<option value="">Select Regency</option>';
                    data.forEach(item => { options += `<option value="${item.id}">${item.name}</option>`; });
                    $('#regency_id').html(options).prop('disabled', false).trigger('change');
                });
            }
            triggerCalculation();
        });

        $('#regency_id').on('change', function() {
            const id = $(this).val();
            $('#district_id, #village_id').html('<option value="">Select...</option>').prop('disabled', true).trigger('change');
            if (id) {
                $.get("{{ url('api/regencies') }}/" + id + "/districts", function(data) {
                    let options = '<option value="">Select District</option>';
                    data.forEach(item => { options += `<option value="${item.id}">${item.name}</option>`; });
                    $('#district_id').html(options).prop('disabled', false).trigger('change');
                });
            }
        });

        $('#district_id').on('change', function() {
            const id = $(this).val();
            $('#village_id').html('<option value="">Select...</option>').prop('disabled', true).trigger('change');
            if (id) {
                $.get("{{ url('api/districts') }}/" + id + "/villages", function(data) {
                    let options = '<option value="">Select Village</option>';
                    data.forEach(item => { options += `<option value="${item.id}">${item.id} - ${item.name}</option>`; });
                    $('#village_id').html(options).prop('disabled', false).trigger('change');
                });
            }
        });

        $('#village_id').on('change', function() {
            $('#res_kode_desa').text($(this).val() || '-');
        });

        // Medan Coefficient
        $.get("{{ route('koef-medan.datatables') }}", function(res) {
            let options = '<option value="">Select Medan Coefficient</option>';
            res.data.forEach(item => { 
                options += `<option value="${item.kode_medan}">${item.kode_medan} (${item.km})</option>`; 
            });
            $('#kode_medan').html(options).trigger('change');
        });

        // Armada -> Cap
        $('#jenis_armada').change(function() {
            const val = $(this).val();
            const cap = armadaCapMap[val] || 0;
            $('#val_cap').val(cap);
            triggerCalculation();
        });

        // Number formatting
        $('.input-format-number').on('input', function() {
            var val = $(this).val().replace(/[^0-9]/g, '');
            if (val !== '') {
                $(this).val(new Intl.NumberFormat('en-US').format(val));
                $('#val_nilai_faktur').val(val);
            } else {
                $(this).val('');
                $('#val_nilai_faktur').val(0);
            }
            triggerCalculation();
        });

        // Trigger on any change
        $('input, select').on('input change', function() {
            if (!$(this).hasClass('select2') && $(this).attr('id') !== 'input_nilai_faktur') {
                triggerCalculation();
            }
        });

        function triggerCalculation() {
            const formData = $('#shipping-simulator-form').serialize();
            $.ajax({
                url: "{{ route('shipping-simulator.calculate') }}",
                type: "POST",
                data: formData,
                success: function(res) {
                    const fmt = (val) => new Intl.NumberFormat('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(val);
                    const fmtInt = (val) => new Intl.NumberFormat('en-US').format(val);

                    $('#res_ckm_z').text(fmtInt(res.ckm_z));
                    $('#res_ct_z').text(fmtInt(res.ct_z));
                    $('#res_tarif_min').text(fmtInt(res.tarif_min));
                    $('#res_alpha_max').text(res.alpha_max);
                    $('#res_ongkir_maks').text(fmtInt(res.ongkir_maks));
                    $('#res_fm').text(fmt(res.fm));
                    $('#res_km_medan').text(fmt(res.km_medan));
                    $('#res_karm').text(fmt(res.karm));
                    $('#res_ongkir_riil').text(fmtInt(res.ongkir_riil));
                    $('#res_ongkir_tagih').text(fmtInt(res.ongkir_tagih));
                    $('#res_setelah_subsidi').text(fmtInt(res.setelah_subsidi));
                    $('#res_ongkir_final').text(fmtInt(res.ongkir_final));
                }
            });
        }
    });
</script>
@endpush
        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
