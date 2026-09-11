<?php $page = 'customer-visit-ranking'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Customer Visit Ranking</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Visit Ranking</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-0 mb-3 shadow-none">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-filter me-2"></i>Filter<i class="ti ti-chevron-down ms-2"></i>
                            </a>
                            <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                    <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select id="filter_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="prospect" selected>Prospect</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" id="filter_start_date" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" id="filter_end_date" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Survey Status</label>
                                        <select id="filter_status" class="form-select">
                                            <option value="">All Statuses</option>
                                            <option value="open">Open</option>
                                            <option value="in-progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="followup">Followup</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Surveyor</label>
                                        <select id="filter_surveyor" class="form-select">
                                            <option value="">All Surveyors</option>
                                            @foreach($surveyors as $surveyor)
                                                <option value="{{ $surveyor->id }}">{{ $surveyor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Export As</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-success w-100" id="export_excel"><i class="ti ti-file-type-xls me-1"></i>Excel</button>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-outline-light w-100" id="filter_reset">Reset</button>
                                        <button type="button" class="btn btn-primary w-100" id="filter_apply">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="customer_visit_ranking_table">
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Total Visits</th>
                                    <th>Last Visit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="row align-items-center mt-3">
                        <div class="col-md-6"><div class="datatable-length"></div></div>
                        <div class="col-md-6"><div class="datatable-paginate"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit History Modal -->
    <div class="modal fade" id="visitHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="fw-semibold">Riwayat Kunjungan <span id="visit-history-customer-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="visit-history-body">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#customer_visit_ranking_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reports.customer-visit.datatables') }}",
                data: function(d) {
                    d.type = $('#filter_type').val();
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                    d.status = $('#filter_status').val();
                    d.surveyor = $('#filter_surveyor').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type' },
                { data: 'visit_count', name: 'visit_count' },
                { data: 'last_visit_at', name: 'last_visit_at', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[3, 'desc']],
            dom: '<"top">rt<"bottom"lp><"clear">',
            initComplete: function () {
                $('.datatable-length').append($('.dataTables_length'));
                $('.datatable-paginate').append($('.dataTables_paginate'));
            }
        });

        $('#filter_apply').on('click', function() {
            table.draw();
        });

        $('#filter_reset').on('click', function() {
            $('#filter_type').val('prospect');
            $('#filter_start_date, #filter_end_date').val('');
            $('#filter_status').val('');
            $('#filter_surveyor').val('');
            table.draw();
        });

        $('#export_excel').on('click', function() {
            const params = $.param({
                type: $('#filter_type').val(),
                start_date: $('#filter_start_date').val(),
                end_date: $('#filter_end_date').val(),
                status: $('#filter_status').val(),
                surveyor: $('#filter_surveyor').val()
            });
            window.location.href = "{{ route('reports.customer-visit.export.excel') }}?" + params;
        });

        const visitsUrlTemplate = "{{ route('reports.customer-visit.visits', ['customer' => '__ID__']) }}";

        function statusBadge(status) {
            const label = status || 'open';
            const badgeClass = {
                'completed': 'bg-success',
                'in-progress': 'bg-warning',
                'followup': 'bg-info'
            }[label] || 'bg-secondary';
            return '<span class="badge ' + badgeClass + '">' + label.charAt(0).toUpperCase() + label.slice(1) + '</span>';
        }

        $(document).on('click', '.btn-view-visits', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const body = $('#visit-history-body');

            $('#visit-history-customer-name').text('- ' + name);
            body.html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $('#visitHistoryModal').modal('show');

            $.get(visitsUrlTemplate.replace('__ID__', id), function(res) {
                if (!res.success || res.visits.length === 0) {
                    body.html('<div class="alert alert-info mb-0">Belum ada data kunjungan untuk customer ini.</div>');
                    return;
                }

                let html = '<p class="text-muted mb-3">Total <strong>' + res.total_visits + '</strong> kunjungan tercatat, diurutkan dari kunjungan pertama.</p>';
                html += '<div class="accordion" id="visitAccordion">';

                res.visits.forEach(function(v, idx) {
                    const label = idx === 0
                        ? 'Kunjungan Pertama'
                        : (idx === res.visits.length - 1 ? 'Kunjungan Terakhir' : 'Kunjungan ke-' + v.no);
                    const evidenceHtml = v.evidence
                        ? '<div class="col-md-12 mt-2"><label class="fw-bold text-muted small">Evidence Kunjungan</label><br>' +
                          '<a href="' + v.evidence + '" target="_blank"><img src="' + v.evidence + '" style="max-width:200px;max-height:150px;object-fit:cover;" class="rounded border"></a></div>'
                        : '';

                    let penutupHtml = '<div class="col-12 mt-2"><p class="text-muted small mb-0">Belum ada data penutup/ringkasan survey.</p></div>';
                    if (v.penutup) {
                        const p = v.penutup;
                        const dokumentasiHtml = p.dokumentasi
                            ? '<div class="col-md-12 mt-2"><label class="fw-bold text-muted small">Dokumentasi</label><br>' +
                              '<a href="' + p.dokumentasi + '" target="_blank"><img src="' + p.dokumentasi + '" style="max-width:200px;max-height:150px;object-fit:cover;" class="rounded border"></a></div>'
                            : '';
                        penutupHtml = '' +
                            '<div class="col-md-12 mb-2"><label class="fw-bold text-muted small">Ringkasan Kebutuhan & Solusi</label><br>' + (p.ringkasan_kebutuhan_solusi || '-') + '</div>' +
                            '<div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Komitmen Tindak Lanjut</label><br>' + (p.komitmen_apa || '-') + '</div>' +
                            '<div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Oleh Siapa</label><br>' + (p.komitmen_oleh_siapa || '-') + '</div>' +
                            '<div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Kapan</label><br>' + ([p.komitmen_kapan_tanggal, p.komitmen_kapan_jam].filter(Boolean).join(' ') || '-') + '</div>' +
                            '<div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Jadwal Follow-up</label><br>' + ([p.followup_tanggal, p.followup_jam].filter(Boolean).join(' ') || '-') + '</div>' +
                            '<div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Kanal Follow-up</label><br>' + (p.followup_kanal || '-') + '</div>' +
                            dokumentasiHtml;
                    }

                    html += '' +
                        '<div class="accordion-item">' +
                        '  <h2 class="accordion-header">' +
                        '    <button class="accordion-button ' + (idx === 0 ? '' : 'collapsed') + '" type="button" data-bs-toggle="collapse" data-bs-target="#visit-' + v.no + '">' +
                        '      <span class="badge bg-primary me-2">' + v.no + '</span> ' + label + ' &mdash; ' + v.visit_date +
                        '    </button>' +
                        '  </h2>' +
                        '  <div id="visit-' + v.no + '" class="accordion-collapse collapse ' + (idx === 0 ? 'show' : '') + '" data-bs-parent="#visitAccordion">' +
                        '    <div class="accordion-body">' +
                        '      <div class="row">' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Status</label><br>' + statusBadge(v.status) + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Jenis Kontak</label><br>' + (v.jenis_kontak || '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Jabatan</label><br>' + (v.jabatan || '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">No WhatsApp</label><br>' + (v.no_wa || '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Komoditas Utama</label><br>' + (v.komoditas || '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Luas Lahan</label><br>' + (v.luas_lahan ? v.luas_lahan + ' Ha' : '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Sistem Irigasi</label><br>' + (v.sistem_irigasi || '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Petugas Survey</label><br>' + (v.surveyor || '-') + '</div>' +
                        '        <div class="col-md-4 mb-2"><label class="fw-bold text-muted small">Follow-up User</label><br>' + (v.followup_user || '-') + '</div>' +
                        '        <div class="col-md-12 mb-2"><label class="fw-bold text-muted small">Alamat Lahan Usaha</label><br>' + (v.alamat || '-') + '</div>' +
                        evidenceHtml +
                        '      </div>' +
                        '      <hr>' +
                        '      <h6 class="text-primary mb-3">Penutup & Ringkasan</h6>' +
                        '      <div class="row">' +
                        penutupHtml +
                        '      </div>' +
                        '    </div>' +
                        '  </div>' +
                        '</div>';
                });

                html += '</div>';
                body.html(html);
            }).fail(function() {
                body.html('<div class="alert alert-danger mb-0">Gagal memuat riwayat kunjungan.</div>');
            });
        });
    });
</script>
@endpush
