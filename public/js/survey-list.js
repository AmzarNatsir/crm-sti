$(document).ready(function () {
    // Initialize DataTable
    const table = $('#survey-list-table').DataTable({
        searchDelay: 500,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/surveys/datatables",
            type: "GET",
            data: function (d) {
                d.jenisKontak = $('#filter_contact_type').val();
                d.userId = $('#filter_surveyor').val();
                d.commodity = $('#filter_commodity').val();
                d.namaLengkap = $('#filter_name').val();
                d.noIdentity = $('#filter_identity_no').val();
                d.noWa = $('#filter_wa').val();
            }
        },
        columns: [
            { data: 'namaLengkap', name: 'namaLengkap' },
            { data: 'jenisKontak', name: 'jenisKontak' },
            { data: 'komoditasUtama', name: 'komoditasUtama' },
            { data: 'kecamatan', name: 'kecamatan' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'surveyor', name: 'surveyor' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
        ],
        order: [[4, 'desc']], // Default sort by Created Date
        dom: '<"top"i>rt<"bottom"lp><"clear">', // Removed 'f' for filter box as we use custom search
        language: {
            paginate: {
                previous: '<i class="ti ti-chevron-left"></i>',
                next: '<i class="ti ti-chevron-right"></i>'
            }
        },
        initComplete: function () {
            // Move pagination to our custom wrappers
            $('.datatable-length').append($('.dataTables_length'));
            $('.datatable-paginate').append($('.dataTables_paginate'));
        }
    });

    console.log('Survey list script loaded');

    // Custom Search
    $('#survey-search').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Filter Buttons
    $(document).on('click', '#filter_apply', function () {
        table.draw();
    });

    $(document).on('click', '#filter_reset', function () {
        $('#filter_contact_type').val('').trigger('change');
        $('#filter_surveyor').val('').trigger('change');
        $('#filter_commodity').val('').trigger('change');
        $('#filter_name').val('');
        $('#filter_identity_no').val('');
        $('#filter_wa').val('');
        table.draw();
    });

    // Preview Event
    // Store original dropdown HTML
    let originalFollowupDropdown = '';

    $(document).ready(function () {
        originalFollowupDropdown = $('#followup-user-select').prop('outerHTML');
    });

    $(document).on('click', '.btn-preview-survey', function () {
        const uid = $(this).data('uid');
        const modal = $('#previewSurveyModal');
        const body = $('#preview-survey-body');
        const editBtn = $('#preview-edit-btn');
        const promoteBtn = $('#preview-promote-btn');
        const promoteWrapper = $('#promote-btn-wrapper');
        const assignBtn = $('#btn-assign-followup');

        // Reset state - restore dropdown if it was replaced
        if ($('#followup-user-text').length) {
            $('#followup-user-text').replaceWith(originalFollowupDropdown);
        }

        const followupSelect = $('#followup-user-select');
        body.html('<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>');
        editBtn.attr('href', `/surveys/create?survey_uid=${uid}&step=1`);
        promoteWrapper.hide();
        followupSelect.val('');
        assignBtn.data('uid', uid);
        modal.modal('show');

        // Fetch details
        $.get(`/surveys/${uid}`, function (res) {
            if (res.success) {
                const data = res.data;
                const isPromoted = res.is_promoted;
                const isCompleted = (data.status === 'completed');

                // Set follow-up user if assigned
                if (data.followup_user_id) {
                    followupSelect.val(data.followup_user_id);
                }

                // Hide/show buttons based on status
                if (isCompleted || isPromoted) {
                    // Hide edit button by setting href to '#' and adding disabled class
                    editBtn.hide();
                    promoteWrapper.hide();
                    assignBtn.hide();

                    // Show follow-up user as text instead of dropdown
                    if (data.followup_user && data.followup_user.name) {
                        followupSelect.replaceWith('<span id="followup-user-text" class="fw-bold">' + data.followup_user.name + '</span>');
                    }
                } else {
                    // Restore dropdown if it was replaced
                    if ($('#followup-user-text').length) {
                        $('#followup-user-text').replaceWith(followupSelect);
                    }
                    editBtn.show();
                    assignBtn.show();

                    if (!isPromoted) {
                        promoteBtn.data('uid', uid);
                        promoteWrapper.show();
                    } else {
                        promoteWrapper.hide();
                    }
                }

                let html = `
                    <div class="survey-preview-container p-3">
                        <div class="nav-tabs-container mb-4">
                            <ul class="nav nav-tabs nav-tabs-solid nav-justified" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#p-step-1">Step 1: General</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-step-2">Step 2: Specific Details</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-step-3">Step 3: Problems</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-step-4">Step 4: Statistics</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-step-5">Step 5: Summary</a></li>
                            </ul>
                        </div>
                        
                        <div class="tab-content pt-2">
                            <!-- Step 1 -->
                            <div class="tab-pane active" id="p-step-1">
                                <div class="row">
                                    ${renderField('Full Name', data.namaLengkap)}
                                    ${renderField('Contact Type', data.jenisKontak)}
                                    ${renderField('Position', data.jabatan)}
                                    ${renderField('WhatsApp', data.noWa)}
                                    ${renderField('Coordinates', data.titikKoordinat)}
                                    ${renderField('Commodity', data.komoditasUtama)}
                                    ${renderField('Land Area', (data.luasLahan ? data.luasLahan + ' Ha' : '-'))}
                                    ${renderField('Irrigation', data.sistemIrigasi)}
                                    ${renderField('Location', `${data.desa || '-'}, ${data.kecamatan || '-'}, ${data.kabupaten || '-'}`)}
                                    <div class="col-md-12 mt-2">
                                        <label class="fw-bold text-muted small uppercase">Address</label>
                                        <p>${data.alamatLahanUsaha || '-'}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="tab-pane" id="p-step-2">
                                <h6 class="text-primary mb-3">${data.jenisKontak} Form Data</h6>
                                <div class="row">
                                    ${renderStep2Data(data)}
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="tab-pane" id="p-step-3">
                                <h6 class="text-primary mb-3">Problem Resolution</h6>
                                <div class="row">
                                    ${renderStep3Data(data.penyelesaianMasalah)}
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="tab-pane" id="p-step-4">
                                <h6 class="text-primary mb-3">Agricultural Statistics</h6>
                                <div class="row">
                                    ${renderStep4Data(data.statistik)}
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="tab-pane" id="p-step-5">
                                <h6 class="text-primary mb-3">Closing & Summary</h6>
                                <div class="row">
                                    ${renderStep5Data(data.penutup)}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                body.html(html);
            } else {
                body.html('<div class="alert alert-danger">Failed to load survey details.</div>');
            }
        }).fail(function () {
            body.html('<div class="alert alert-danger">Error connecting to server.</div>');
        });

        function renderField(label, value) {
            return `
                <div class="col-md-4 mb-3">
                    <label class="fw-bold text-muted small uppercase">${label}</label>
                    <p class="mb-0 text-dark">${value || '-'}</p>
                </div>
            `;
        }

        function renderStep2Data(data) {
            const type = data.jenisKontak;
            if (type === 'STI Customer' && data.pelangganSTI) {
                const s = data.pelangganSTI;
                return `
                    ${renderField('Used Product', s.produkStiYangDigunakan_Nama)}
                    ${renderField('Batch Number', s.produkStiYangDigunakan_Batch)}
                    ${renderField('App Date', s.produkStiYangDigunakan_TanggalApplikasi)}
                    ${renderField('Dosage', s.produkStiYangDigunakan_DosisCaraPakai)}
                    ${renderField('Growth', s.perkembanganTanaman_Pertumbuhan)}
                    ${renderField('Green Leaf', s.perkembanganTanaman_HijauDaun)}
                    ${renderField('Satisfaction (1-10)', s.kepuasanTerhadapProdukLayanan_Nilai)}
                    <div class="col-md-8">
                        <label class="fw-bold text-muted small uppercase">Satisfaction Reason</label>
                        <p>${s.kepuasanTerhadapProdukLayanan_Alasan || '-'}</p>
                    </div>
                `;
            } else if (type === 'Shop/Retailer' && data.tokoPengecer) {
                const s = data.tokoPengecer;
                return `
                    ${renderField('Shop Name', s.profil_NamaToko)}
                    ${renderField('Channel', s.profil_KanalPenjualan)}
                    ${renderField('Monthly Vol', s.profil_VolumePenjualanBulanan)}
                    ${renderField('Brands Sold', s.profil_MerekYangDijual)}
                    ${renderField('STI Interest', s.kebutuhanKetertarikan_ProdukSti)}
                    ${renderField('Margin Expectation', s.kebutuhanKetertarikan_Margin)}
                `;
            } else if (type === 'Farmer Prospect' && data.prospekPetani) {
                const s = data.prospekPetani;
                return `
                    ${renderField('Main Challenge', s.tantanganUtamaSaatIni)}
                    ${renderField('Current Solution', s.solusi_ProdukMerek)}
                    ${renderField('Target yield', s.rencanaTanamAnggaran_TargetHasil)}
                    ${renderField('Planting Deadline', s.rencanaTanamAnggaran_BatasWaktuTanam)}
                    ${renderField('Commitment', s.komitmenAwal)}
                `;
            } else if (type === 'Partner/Collector' && data.mitraPengepul) {
                const s = data.mitraPengepul;
                return `
                    ${renderField('Business Name', s.profil_NamaUsaha)}
                    ${renderField('Commodity', s.profil_KomoditasUtama)}
                    ${renderField('Consistency Need', s.kebutuhan_KonsistensiPasokan)}
                    ${renderField('Quality Need', s.kebutuhan_Kualitas)}
                `;
            } else if (type === 'Farmer Group Head' && data.ketuaPoktan) {
                const s = data.ketuaPoktan;
                return `
                    ${renderField('Group Name', s.profil_Nama)}
                    ${renderField('Members count', s.profil_JumlahAnggota)}
                    ${renderField('Total Area', s.profil_TotalLuasTanam)}
                    ${renderField('Demo Plot Interest', s.ketertarikan_DemoPlot)}
                `;
            }
            return '<div class="col-12"><p class="text-muted italic">Specific data not available.</p></div>';
        }

        function renderStep3Data(s) {
            if (!s) return '<div class="col-12"><p class="text-muted italic">Problem resolution data not recorded.</p></div>';
            return `
                ${renderField('Since When', s.deskripsi_SejakKapan)}
                ${renderField('Plant Stage', s.deskripsi_TahapanTanaman)}
                ${renderField('Affected Area', s.dampak_LuasAreaTerdampak)}
                ${renderField('Yield Reduction', s.dampak_EstimasiPotensiPenurunanHasil)}
                ${renderField('Root Cause', s.akarDugaan)}
                ${renderField('Ticket Status', s.statusTiket)}
                <div class="col-md-12 mb-3">
                    <label class="fw-bold text-muted small uppercase">Product History</label>
                    <p class="mb-0 text-dark">${s.riwayatTindakan_ProdukSolusi || '-'}</p>
                </div>
                ${renderField('Solution Package', s.rencanaAksiDisepakati_PaketRekomendasi)}
                ${renderField('Action By', s.rencanaAksiDisepakati_Siapa)}
                ${renderField('SLA Date', s.slaPemantauan_Tanggal)}
                ${renderField('SLA Time', s.slaPemantauan_Jam)}
            `;
        }

        function renderStep4Data(s) {
            if (!s) return '<div class="col-12"><p class="text-muted italic">Statistical data not recorded.</p></div>';
            return `
                ${renderField('Rainfall', s.curahHujan)}
                ${renderField('Extr Weather', s.kejadianEkstrem)}
                ${renderField('Input Prices', s.harga_TrenHargaPupukBenihPestisida)}
                ${renderField('Yield Prices', s.harga_HargaJualHasilPanen)}
                ${renderField('New Variety', s.perubahanPraktikBudidaya_VarietasBaru)}
                ${renderField('Machinery', s.perubahanPraktikBudidaya_PenggunaanMesin)}
                ${renderField('Info Media', s.sumberInformasiPetani_Media)}
                ${renderField('Info Figures', s.sumberInformasiPetani_TokohLokal)}
                ${renderField('Info Counselor', s.sumberInformasiPetani_Penyuluh)}
            `;
        }

        function renderStep5Data(s) {
            if (!s) return '<div class="col-12"><p class="text-muted italic">Closing summary not available.</p></div>';
            return `
                <div class="col-md-12 mb-3">
                    <label class="fw-bold text-muted small uppercase">Needs & Solutions Summary</label>
                    <p class="text-dark">${s.ringkasanKebutuhanSolusi || '-'}</p>
                </div>
                <hr>
                <div class="col-12 mb-3"><h6 class="text-primary">Commitment & Follow-up</h6></div>
                ${renderField('Follow-up Action', s.komitmenTindakLanjut_Apa)}
                ${renderField('By Whome', s.komitmenTindakLanjut_OlehSiapa)}
                ${renderField('Target Date', s.komitmenTindakLanjut_KapanTanggal)}
                ${renderField('Follow-up Date', s.jadwalFollowup_Tanggal)}
                ${renderField('Channel', s.jadwalFollowup_Kanal)}
                <div class="col-md-12 mt-3">
                    <label class="fw-bold text-muted small uppercase">Notes</label>
                    <p class="text-dark">${s.catatanTambahan || '-'}</p>
                </div>
            `;
        }
    });

    // Promote to Prospect Action
    $(document).on('click', '#preview-promote-btn', function () {
        const uid = $(this).data('uid');
        const btn = $(this);

        Swal.fire({
            title: 'Promote to Prospect?',
            text: "This contact will be added to the Customer database as a Prospect.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Promote',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `/surveys/${uid}/promote`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error.responseJSON ? error.responseJSON.message : error.statusText}`
                    )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire({
                    title: 'Success!',
                    text: result.value.message,
                    icon: 'success'
                });
                $('#promote-btn-wrapper').hide();
                table.draw(false); // Refresh table if needed, though state is in modal
            }
        });
    });

    // Assign Follow-up User
    $(document).on('click', '#btn-assign-followup', function () {
        const uid = $(this).data('uid');
        const userId = $('#followup-user-select').val();
        const btn = $(this);

        if (!userId) {
            Swal.fire({
                title: 'No User Selected',
                text: 'Please select a user to assign for follow-up.',
                icon: 'warning'
            });
            return;
        }

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Assigning...');

        $.ajax({
            url: `/surveys/${uid}/assign-followup`,
            type: 'POST',
            data: {
                followup_user_id: userId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: res.message,
                        icon: 'success',
                        timer: 2000
                    });
                    table.draw(false); // Refresh table
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: res.message || 'Failed to assign follow-up user.',
                        icon: 'error'
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to assign follow-up user.',
                    icon: 'error'
                });
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="ti ti-user-check me-1"></i>Assign');
            }
        });
    });

    // Repeat functionality is handled via simple <a> link in the controller, 
    // but we can add UI feedback if needed.
});
