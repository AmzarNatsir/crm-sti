@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content pb-0">
        <div class="mb-4">
            <h4 class="mb-1">Admin Survey Entry</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Survey</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="survey-wizard">
                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 4px;">
                        <div id="survey-progress" class="progress-bar bg-success" role="progressbar" style="width: 20%;"></div>
                    </div>

                    <!-- Step Indicators -->
                    <ul class="nav nav-pills nav-justified mb-4" id="survey-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-step="1">Bagian Umum</a>
                        </li>
                        <li class="nav-item d-none" id="step-2-tab-li">
                            <a class="nav-link disabled" data-step="2">Client Specific</a>
                        </li>
                        <li class="nav-link disabled" data-step="3">Penyelesaian Masalah</a>
                        <li class="nav-link disabled" data-step="4">Statistik</a>
                        <li class="nav-link disabled" data-step="5">Penutup & Rangkuman</a>
                    </ul>

                    <form id="survey-form" action="{{ route('admin-surveys.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="survey_uid" id="survey_uid">
                        <input type="hidden" name="step" id="current_step" value="1">

                        <!-- Step 1: General Section -->
                        <div class="step-content" id="step-1">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Surveyor <span class="text-danger">*</span></label>
                                    <select name="userId" class="form-select select2" required>
                                        <option value="">Select Surveyor</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kontak <span class="text-danger">*</span></label>
                                    <select name="jenisKontak" class="form-select" required id="jenisKontak">
                                        <option value="">Pilihan Jenis Kontak</option>
                                        <option value="Farmer Prospect">Prospek Petani</option>
                                        <option value="STI Customer">Pelanggan STI</option>
                                        <option value="Shop/Retailer">Toko/Pengecer</option>
                                        <option value="Partner/Collector">Mitra/Pengepul </option>
                                        <option value="Farmer Group Head">Ketua Poktan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="namaLengkap" class="form-control" required placeholder="Nama Lengkap">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Identitas (KTP) <span class="text-danger">*</span></label>
                                    <input type="text" name="noIdentity" class="form-control" required placeholder="Nomor Identitas (KTP)">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tglLahir" class="form-control" placeholder="Tanggal Lahir">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" name="jabatan" class="form-control" required placeholder="Jabatan">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="noWa" class="form-control" required placeholder="08xxxx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Alternatif</label>
                                    <input type="text" name="noAlternatif" class="form-control" placeholder="Optional">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alamat Lahan Usaha<span class="text-danger">*</span></label>
                                    <input type="text" name="alamatLahanUsaha" class="form-control" required placeholder="Alamat Lahan Usaha">
                                </div>

                                <!-- Regional Data -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Provinsi<span class="text-danger">*</span></label>
                                    <select name="provinsiId" id="provinsiId" class="form-select select2" required></select>
                                    <input type="hidden" name="provinsi" id="provinsi_name">
                                    <input type="hidden" name="provinsiKode" id="provinsi_kode">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Kabupaten <span class="text-danger">*</span></label>
                                    <select name="kabupatenId" id="kabupatenId" class="form-select select2" required disabled></select>
                                    <input type="hidden" name="kabupaten" id="kabupaten_name">
                                    <input type="hidden" name="kabupatenKode" id="kabupaten_kode">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                    <select name="kecamatanId" id="kecamatanId" class="form-select select2" required disabled></select>
                                    <input type="hidden" name="kecamatan" id="kecamatan_name">
                                    <input type="hidden" name="kecamatanKode" id="kecamatan_kode">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Desa <span class="text-danger">*</span></label>
                                    <select name="desaId" id="desaId" class="form-select select2" required disabled></select>
                                    <input type="hidden" name="desa" id="desa_name">
                                    <input type="hidden" name="desaKode" id="desa_kode">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Titik Koordinat</label>
                                    <input type="text" name="titikKoordinat" class="form-control" placeholder="-6.xxx, 106.xxx">
                                </div>
                                <!-- New Fields -->
                                <div class="col-md-5 mb-3">
                                    <label class="form-label">Komoditas Utama <span class="text-danger">*</span></label>
                                    <select name="komoditasUtama" class="form-select select2" id="komoditasUtama" required>
                                        <option value="">Pilih Komoditas</option>
                                        @foreach($commodities as $commodity)
                                            <option value="{{ $commodity->name }}">{{ $commodity->name }}</option>
                                        @endforeach
                                        <option value="Lainnya">Lainnya/Other</option>
                                    </select>
                                    <input type="text" name="komoditasUtamaLainnya" class="form-control mt-2 d-none" id="komoditasUtamaLainnya" placeholder="Sebutkan Komoditas Lainnya">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Luas Lahan (Ha) <span class="text-danger">*</span></label>
                                    <input type="number" name="luasLahan" class="form-control" step="0.01" required placeholder="Contoh: 1.5">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sistem Irigasi</label>
                                    <select name="sistemIrigasi" class="form-select" id="sistemIrigasi">
                                        <option value="">Pilih Sistem Irigasi</option>
                                        <option value="Tadah Hujan">Tadah Hujan (Rainfed)</option>
                                        <option value="Irigasi Teknis">Irigasi Teknis (Technical Irrigation)</option>
                                        <option value="Sumur">Sumur (Well)</option>
                                        <option value="Lainnya">Lainnya/Other</option>
                                    </select>
                                    <input type="text" name="sistemIrigasiLainnya" class="form-control mt-2 d-none" id="sistemIrigasiLainnya" placeholder="Sebutkan Sistem Irigasi Lainnya">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Musim Tanam</label>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Tanggal Tanam</label>
                                            <input type="date" name="musimTanamTanggal" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Perkiraan Panen</label>
                                            <input type="date" name="musimTanamPerkiraanPanen" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small">Tahap Pertumbuhan</label>
                                            <select name="musimTanamTahapPertumbuhan" class="form-select">
                                                <option value="">Pilih Tahap</option>
                                                <option value="Seed/Seedling">Benih/Bibit (Seed/Seedling)</option>
                                                <option value="Vegetative">Vegetatif</option>
                                                <option value="Flowering">Berbunga (Flowering)</option>
                                                <option value="Filling">Pengisian (Filling)</option>
                                                <option value="Near Harvest">Menjelang Panen (Near Harvest)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Sumber Mengenal STI</label>
                                            <select name="sumberMengenalSti" class="form-select" id="sumberMengenalSti">
                                                <option value="">Pilih Sumber</option>
                                                <option value="Referral">Referral</option>
                                                <option value="Social Media">Social Media</option>
                                                <option value="Website">Website</option>
                                                <option value="Events/Farmer Meetings">Acara/Pertemuan Petani</option>
                                                <option value="Shops">Toko</option>
                                                <option value="Collectors">Pengepul</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                            <input type="text" name="sumberMengenalStiLainnya" class="form-control mt-2 d-none" id="sumberMengenalStiLainnya" placeholder="Sebutkan Sumber Lainnya">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Evidence Kunjungan</label>
                                    <input type="file" name="evidenceKunjungan" class="form-control" accept="image/*" onchange="previewImage(this)">
                                    <div class="mt-2">
                                        <img id="evidencePreview" src="#" alt="Preview" style="max-height: 150px; display: none;" class="img-thumbnail">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="persetujuanPerekamanPanggilan" id="persetujuanPerekamanPanggilan" value="1" checked>
                                        <label class="form-check-label" for="persetujuanPerekamanPanggilan">
                                            Persetujuan Perekaman Panggilan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="persetujuanPengolahanData" id="persetujuanPengolahanData" value="1" checked>
                                        <label class="form-check-label" for="persetujuanPengolahanData">
                                            Persetujuan Pengolahan Data untuk layanan & penawaran STI
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Specific Forms (Dynamic) -->
                        <div class="step-content d-none" id="step-2">
                            <div id="dynamic-step-2-content">
                                <p class="text-center text-muted">Loading specific form based on contact type...</p>
                            </div>
                        </div>

                        <!-- Step 3: Problem Resolution -->
                        <div class="step-content d-none" id="step-3">
                           <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h5 class="text-primary mb-3">Problem Resolution Form</h5>
                                </div>

                                <!-- Problem Description -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Problem Description</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Since When</label>
                                                    <input type="text" name="deskripsi_SejakKapan" class="form-control" placeholder="e.g. 2 weeks ago">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Plant Stage</label>
                                                    <input type="text" name="deskripsi_TahapanTanaman" class="form-control" placeholder="e.g. Vegetative">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Impact -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Impact</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Affected Area</label>
                                                    <input type="text" name="dampak_LuasAreaTerdampak" class="form-control" placeholder="e.g. 2 Ha">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Est. Yield Reduction</label>
                                                    <input type="text" name="dampak_EstimasiPotensiPenurunanHasil" class="form-control" placeholder="e.g. 20%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action History -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Action History</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Product Solution Used</label>
                                                    <textarea name="riwayatTindakan_ProdukSolusi" class="form-control" rows="1"></textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Dose</label>
                                                    <input type="text" name="riwayatTindakan_Dosis" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Date</label>
                                                    <input type="date" name="riwayatTindakan_Tanggal" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Diagnosis & Support -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Diagnosis & Support</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Suspected Root Cause</label>
                                                    <select name="akarDugaan" class="form-select trigger-other" data-target="#akarDugaan_Lainnya">
                                                        <option value="">Select Cause</option>
                                                        <option value="Nutrients">Nutrients</option>
                                                        <option value="Pests">Pests</option>
                                                        <option value="Water">Water</option>
                                                        <option value="Weather">Weather</option>
                                                        <option value="Cultivation Techniques">Cultivation Techniques</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                    <input type="text" name="akarDugaan_Lainnya" id="akarDugaan_Lainnya" class="form-control mt-2 d-none" placeholder="Specify other cause">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Support Needs</label>
                                                    <select name="kebutuhanDukungan" class="form-select">
                                                        <option value="">Select Support</option>
                                                        <option value="Online Consultation">Online Consultation</option>
                                                        <option value="Agronomic Visit">Agronomic Visit</option>
                                                        <option value="Escalation to Factory">Escalation to Factory</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Plan & SLA -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Action Plan & SLA</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Agreed Recommendation</label>
                                                    <input type="text" name="rencanaAksiDisepakati_PaketRekomendasi" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Who Responsible</label>
                                                    <input type="text" name="rencanaAksiDisepakati_Siapa" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Monitoring Date (SLA)</label>
                                                    <input type="date" name="slaPemantauan_Tanggal" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Monitoring Time (SLA)</label>
                                                    <input type="time" name="slaPemantauan_Jam" class="form-control">
                                                </div>
                                                 <div class="col-md-12 mb-3">
                                                    <label class="form-label">Ticket Status</label>
                                                    <select name="statusTiket" class="form-select">
                                                        <option value="New">New</option>
                                                        <option value="In Progress">In Progress</option>
                                                        <option value="Closed">Closed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           </div>
                        </div>

                        <!-- Step 4: Agricultural Statistics -->
                        <div class="step-content d-none" id="step-4">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h5 class="text-primary mb-3">Agricultural Context & Statistics</h5>
                                </div>

                                <!-- Rainfall & Events -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Rainfall & Weather Events</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Rainfall Pattern</label>
                                                    <select name="curahHujan" class="form-select">
                                                        <option value="">Select Pattern</option>
                                                        <option value="Decrease">Decrease</option>
                                                        <option value="Normal">Normal</option>
                                                        <option value="Increase">Increase</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Extreme Events</label>
                                                    <select name="kejadianEkstrem" class="form-select">
                                                        <option value="">Select Event</option>
                                                        <option value="Flood">Flood</option>
                                                        <option value="Drought">Drought</option>
                                                        <option value="None">None</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Date Recorded</label>
                                                    <input type="date" name="tanggal" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Prices -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Input Prices & Yields</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Trend: Fertilizer/Seed/Pesticides</label>
                                                    <input type="text" name="harga_TrenHargaPupukBenihPestisida" class="form-control" placeholder="e.g. Increasing, Stable">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Trend: Selling Prices</label>
                                                    <input type="text" name="harga_HargaJualHasilPanen" class="form-control" placeholder="e.g. Decreasing, 5000/kg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cultivation Changes -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Changes in Cultivation Practices</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">New Varieties</label>
                                                    <input type="text" name="perubahanPraktikBudidaya_VarietasBaru" class="form-control" placeholder="Describe if any">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Technique Changes</label>
                                                    <input type="text" name="perubahanPraktikBudidaya_PerubahanTeknik" class="form-control" placeholder="Describe if any">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Machine Use</label>
                                                    <input type="text" name="perubahanPraktikBudidaya_PenggunaanMesin" class="form-control" placeholder="Describe if any">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Information Sources -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Information Sources</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Media</label>
                                                    <input type="text" name="sumberInformasiPetani_Media" class="form-control" placeholder="e.g. TV, Radio, Social Media">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Local Figures</label>
                                                    <input type="text" name="sumberInformasiPetani_TokohLokal" class="form-control" placeholder="Name/Role">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Extension Workers</label>
                                                    <input type="text" name="sumberInformasiPetani_Penyuluh" class="form-control" placeholder="Name/Agency">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Closing & Summary -->
                        <div class="step-content d-none" id="step-5">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h5 class="text-primary mb-3">Closing & Summary</h5>
                                </div>

                                <!-- Summary -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Summary of Needs & Solutions</div>
                                        <div class="card-body">
                                            <textarea name="ringkasanKebutuhanSolusi" class="form-control" rows="3" placeholder="Enter summary..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Follow-up Commitment -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Follow-up Commitment/Promise</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">What Commitment?</label>
                                                    <input type="text" name="komitmenTindakLanjut_Apa" class="form-control" placeholder="What was promised?">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Who (Oleh Siapa)?</label>
                                                    <input type="text" name="komitmenTindakLanjut_OlehSiapa" class="form-control" placeholder="Person in charge">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">When (Date)?</label>
                                                    <input type="date" name="komitmenTindakLanjut_KapanTanggal" class="form-control">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">When (Time/Jam)?</label>
                                                    <input type="text" name="komitmenTindakLanjut_KapanJam" class="form-control" placeholder="e.g. 10:00 AM">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Next Follow-up Schedule -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Next Follow-up Schedule</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Follow-up Date</label>
                                                    <input type="date" name="jadwalFollowup_Tanggal" class="form-control">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Follow-up Time</label>
                                                    <input type="time" name="jadwalFollowup_Jam" class="form-control">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Follow-up Channel</label>
                                                    <select name="jadwalFollowup_Kanal" class="form-select">
                                                        <option value="">Select Channel</option>
                                                        <option value="WhatsApp">WhatsApp</option>
                                                        <option value="Call">Call</option>
                                                        <option value="Visit">Visit</option>
                                                        <option value="Email">Email</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documentation -->
                                <div class="col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light fw-bold">Documentation (Optional)</div>
                                        <div class="card-body">
                                            <input type="file" name="dokumentasi" class="form-control" accept="image/*" onchange="previewImage(this, '#closingPreview')">
                                            <div class="mt-2">
                                                <img id="closingPreview" src="#" alt="Preview" style="max-height: 150px; display: none;" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-light d-none" id="prev-btn">Previous</button>
                            <button type="button" class="btn btn-primary" id="next-btn">Next Step</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.loadedSurveyData = null; // Strictly global

    $(document).ready(function() {
        let currentStep = 1;
        const totalSteps = 5;

        // Check for URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const stepParam = parseInt(urlParams.get('step'));
        const uidParam = urlParams.get('survey_uid');

        if (uidParam && stepParam) {
            $('#survey_uid').val(uidParam);
            currentStep = stepParam;

            // Restore state logic
            $.get(`{{ url('surveys') }}/${uidParam}`, function(res) {
                if(res.success) {
                    loadedSurveyData = res.data;
                    restoreAllStepData(loadedSurveyData);
                    navigateStep(stepParam);
                }
            }).fail(function() {
                console.warn("Survey data not found for UID:", uidParam, ". Resetting to Step 1.");
                $('#survey_uid').val('');
                currentStep = 1;
                navigateStep(1);
                // Clean up URL
                window.history.replaceState({}, '', window.location.pathname);
            });
        }

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        $('#jenisKontak').on('change', function() {
            const type = $(this).val();
            if(type) {
                loadSpecificForm(type);
            }
        });

        // Load Provinces
        $.get("{{ route('api.provinces') }}", function(data) {
            let options = '<option value="">Select Province</option>';
            data.forEach(p => {
                options += `<option value="${p.id}" data-name="${p.name}">${p.name}</option>`;
            });
            $('#provinsiId').html(options);
        });

        // Regional Cascades
        $('#provinsiId').change(function() {
            const id = $(this).val();
            const name = $(this).find(':selected').data('name');
            $('#provinsi_name').val(name);
            $('#provinsi_kode').val(id);

            $('#kabupatenId').prop('disabled', true).html('<option>Loading...</option>');
            $('#kecamatanId, #desaId').prop('disabled', true).html('');

            if(id) {
                $.get(`/api/provinces/${id}/regencies`, function(data) {
                    let options = '<option value="">Select Regency</option>';
                    data.forEach(r => options += `<option value="${r.id}" data-name="${r.name}">${r.name}</option>`);
                    $('#kabupatenId').prop('disabled', false).html(options);
                });
            }
        });

        $('#kabupatenId').change(function() {
            const id = $(this).val();
            const name = $(this).find(':selected').data('name');
            $('#kabupaten_name').val(name);
            $('#kabupaten_kode').val(id);

            $('#kecamatanId').prop('disabled', true).html('<option>Loading...</option>');
            $('#desaId').prop('disabled', true).html('');

            if(id) {
                $.get(`/api/regencies/${id}/districts`, function(data) {
                    let options = '<option value="">Select Sub-district</option>';
                    data.forEach(d => options += `<option value="${d.id}" data-name="${d.name}">${d.name}</option>`);
                    $('#kecamatanId').prop('disabled', false).html(options);
                });
            }
        });

        $('#kecamatanId').change(function() {
            const id = $(this).val();
            const name = $(this).find(':selected').data('name');
            $('#kecamatan_name').val(name);
            $('#kecamatan_kode').val(id);

            $('#desaId').prop('disabled', true).html('<option>Loading...</option>');

            if(id) {
                $.get(`/api/districts/${id}/villages`, function(data) {
                    let options = '<option value="">Select Village</option>';
                    data.forEach(v => options += `<option value="${v.id}" data-name="${v.name}">${v.name}</option>`);
                    $('#desaId').prop('disabled', false).html(options);
                });
            }
        });

        $('#desaId').change(function() {
            const name = $(this).find(':selected').data('name');
            const id = $(this).val();
            $('#desa_name').val(name);
            $('#desa_kode').val(id);
        });

        // Wizard Navigation
        $('#next-btn').click(function() {
            const form = $('#survey-form');

            // Basic validation for Step 1
            if(currentStep === 1) {
                if(!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }
            }

            // AJAX submit current step
            $('#current_step').val(currentStep);

            // Use FormData for file upload
            const formData = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.success) {
                        console.log('Step 1 Saved:', res);

                        if(currentStep === 1) {
                            $('#survey_uid').val(res.survey_uid);
                            console.log('Loading specific form for:', res.jenisKontak);
                            loadSpecificForm(res.jenisKontak);
                        }

                        // Update global data for persistence
                        if (res.data) {
                            loadedSurveyData = res.data;
                            // Optionally re-restore everything or just let it be
                            // In some cases, like step 2 dynamic, we might need to refresh
                            if (currentStep === 1) {
                                restoreStep2Data(loadedSurveyData);
                            }
                        }

                        if(currentStep < totalSteps) {
                            console.log('Navigating to step:', currentStep + 1);
                            navigateStep(currentStep + 1);
                        } else {
                            showToast('success', 'Survey completed successfully!');
                            window.location.href = "{{ url('home') }}";
                        }
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON.message || 'Error saving data.');
                }
            });
        });

        $('#prev-btn').click(function() {
            if(currentStep > 1) {
                navigateStep(currentStep - 1);
            }
        });

        function navigateStep(step) {
            $(`.step-content`).addClass('d-none');
            $(`#step-${step}`).removeClass('d-none');

            $(`#survey-tabs .nav-link`).removeClass('active').addClass('disabled');
            $(`#survey-tabs .nav-link[data-step="${step}"]`).addClass('active').removeClass('disabled');

            currentStep = step;
            $('#survey-progress').css('width', `${(step/totalSteps) * 100}%`);

            // Update URL
            const uid = $('#survey_uid').val();
            if(uid) {
                const newUrl = `${window.location.pathname}?step=${step}&survey_uid=${uid}`;
                window.history.pushState({path: newUrl}, '', newUrl);
            }

            if(currentStep > 1) {
                $('#prev-btn').removeClass('d-none');
            } else {
                $('#prev-btn').addClass('d-none');
            }

            if(currentStep === totalSteps) {
                $('#next-btn').text('Finish Survey');
            } else {
                $('#next-btn').text('Next Step');
            }
        }


        function restoreStep2Data(data) {
            if (!data) return;
            const type = data.jenisKontak;

            if (type === 'STI Customer' && data.pelangganSTI) {
                const stiData = data.pelangganSTI;
                $('input[name="produkStiYangDigunakan_Nama"]').val(stiData.produkStiYangDigunakan_Nama);
                $('input[name="produkStiYangDigunakan_Batch"]').val(stiData.produkStiYangDigunakan_Batch);
                $('input[name="produkStiYangDigunakan_TanggalApplikasi"]').val(stiData.produkStiYangDigunakan_TanggalApplikasi);
                $('input[name="produkStiYangDigunakan_DosisCaraPakai"]').val(stiData.produkStiYangDigunakan_DosisCaraPakai);

                ['perkembanganTanaman_Pertumbuhan', 'perkembanganTanaman_HijauDaun', 'perkembanganTanaman_Akar', 'perkembanganTanaman_BungaPolongBuah', 'masalahYangMuncul_Keparahan', 'tindakanKorektif_HasilAwal', 'kepuasanTerhadapProdukLayanan_Nilai'].forEach(field => {
                    $(`input[name="${field}"]`).val(stiData[field] || 0).trigger('input');
                });

                $('select[name="kondisiCuaca"]').val(stiData.kondisiCuaca);
                $('input[name="kondisiCuaca_Catatan"]').val(stiData.kondisiCuaca_Catatan);
                $('select[name="masalahYangMuncul_Jenis"]').val(stiData.masalahYangMuncul_Jenis);
                $('input[name="masalahYangMuncul_LuasTerdampak"]').val(stiData.masalahYangMuncul_LuasTerdampak);

                if(stiData.masalahYangMuncul_Photo) {
                    const baseUrl = "{{ asset('/') }}";
                    $('#probPreview').attr('src', baseUrl + stiData.masalahYangMuncul_Photo).show();
                }

                $('textarea[name="tindakanKorektif_Apa"]').val(stiData.tindakanKorektif_Apa);
                $('input[name="tindakanKorektif_Kapan"]').val(stiData.tindakanKorektif_Kapan);

                if(stiData.butuhPendampingan == 1) {
                    $('#butuhPendampingan').prop('checked', true).trigger('change');
                }
                $('input[name="butuhPendampingan_Jadwal"]').val(stiData.butuhPendampingan_Jadwal);
                $('input[name="butuhPendampingan_Lokasi"]').val(stiData.butuhPendampingan_Lokasi);
                $('textarea[name="butuhPendampingan_Tujuan"]').val(stiData.butuhPendampingan_Tujuan);

                $('input[name="perkiraanHasil"]').val(stiData.perkiraanHasil);
                $('input[name="rencanaPanen"]').val(stiData.rencanaPanen);
                $('input[name="kepuasanTerhadapProdukLayanan_Alasan"]').val(stiData.kepuasanTerhadapProdukLayanan_Alasan);

                if(stiData.minatIkutLanjutProgramReward == 1) {
                    $('#minatIkutLanjutProgramReward').prop('checked', true);
                }

                if(stiData.memberGetMember == 1) {
                    $('#memberGetMember').prop('checked', true).trigger('change');
                }
                $('input[name="memberGetMember_Referal"]').val(stiData.memberGetMember_Referal);

                $('input[name="nextStep_TindakLanjut"]').val(stiData.nextStep_TindakLanjut);
                $('input[name="nextStep_WaktuFollowup"]').val(stiData.nextStep_WaktuFollowup);

            } else if (type === 'Shop/Retailer' && data.tokoPengecer) {
                const retailerData = data.tokoPengecer;
                $('input[name="profil_NamaToko"]').val(retailerData.profil_NamaToko);
                $('textarea[name="profil_Alamat"]').val(retailerData.profil_Alamat);
                $('select[name="profil_KanalPenjualan"]').val(retailerData.profil_KanalPenjualan);
                $('select[name="profil_VolumePenjualanBulanan"]').val(retailerData.profil_VolumePenjualanBulanan);
                $('textarea[name="profil_MerekYangDijual"]').val(retailerData.profil_MerekYangDijual);

                $('textarea[name="kebutuhanKetertarikan_ProdukSti"]').val(retailerData.kebutuhanKetertarikan_ProdukSti);
                $('input[name="kebutuhanKetertarikan_Margin"]').val(retailerData.kebutuhanKetertarikan_Margin);
                $('input[name="kebutuhanKetertarikan_SyaratPembayaran"]').val(retailerData.kebutuhanKetertarikan_SyaratPembayaran);
                $('input[name="kebutuhanKetertarikan_DukunganPromosi"]').val(retailerData.kebutuhanKetertarikan_DukunganPromosi);

                $('select[name="kesediaanProgram_DisplayMateri"]').val(retailerData.kesediaanProgram_DisplayMateri);
                $('input[name="kesediaanProgram_StokAwal"]').val(retailerData.kesediaanProgram_StokAwal);
                $('select[name="kesediaanProgram_DemoPlot"]').val(retailerData.kesediaanProgram_DemoPlot);
                $('select[name="kesediaanProgram_ProgramPoin"]').val(retailerData.kesediaanProgram_ProgramPoin);

                if(retailerData.rencanaKerjasama_POAwal == 1) {
                    $('#rencanaKerjasama_POAwal').prop('checked', true).trigger('change');
                }
                $('input[name="rencanaKerjasama_POAwal_Estimasi"]').val(retailerData.rencanaKerjasama_POAwal_Estimasi);
                $('input[name="rencanaKerjasama_JadwalPelatihan"]').val(retailerData.rencanaKerjasama_JadwalPelatihan);
                $('input[name="rencanaKerjasama_TargetTigaBulan"]').val(retailerData.rencanaKerjasama_TargetTigaBulan);

                $('input[name="memberGetMember_Nama"]').val(retailerData.memberGetMember_Nama);
                $('input[name="memberGetMember_Kontak"]').val(retailerData.memberGetMember_Kontak);

            } else if (type === 'Partner/Collector' && data.mitraPengepul) {
                const partnerData = data.mitraPengepul;
                $('input[name="profil_NamaUsaha"]').val(partnerData.profil_NamaUsaha);
                $('input[name="profil_KomoditasUtama"]').val(partnerData.profil_KomoditasUtama);
                $('input[name="profil_WilayahJangkauan"]').val(partnerData.profil_WilayahJangkauan);

                if(partnerData.profil_Musiman == 1) {
                    $('#profil_Musiman').prop('checked', true);
                }

                $('select[name="kebutuhan_KonsistensiPasokan"]').val(partnerData.kebutuhan_KonsistensiPasokan);
                $('select[name="kebutuhan_Kualitas"]').val(partnerData.kebutuhan_Kualitas);
                $('textarea[name="kebutuhan_DukunganBudidaya"]').val(partnerData.kebutuhan_DukunganBudidaya);

                $('select[name="modelKerjasama_SkemaKemitraan"]').val(partnerData.modelKerjasama_SkemaKemitraan);
                $('select[name="modelKerjasama_KeterlibatanProgram"]').val(partnerData.modelKerjasama_KeterlibatanProgram);
                $('textarea[name="modelKerjasama_DukunganLogistikEdukasi"]').val(partnerData.modelKerjasama_DukunganLogistikEdukasi);

                if(partnerData.potensiIntegrasiDataPanen == 1) {
                    $('#potensiIntegrasiDataPanen').prop('checked', true);
                }

                $('input[name="komitmenAwal_PertemuanSelanjutnya"]').val(partnerData.komitmenAwal_PertemuanSelanjutnya);
                $('textarea[name="komitmenAwal_DataYangDibutuhkan"]').val(partnerData.komitmenAwal_DataYangDibutuhkan);
                $('input[name="komitmenAwal_PicTeknis"]').val(partnerData.komitmenAwal_PicTeknis);

            } else if (type === 'Farmer Group Head' && data.ketuaPoktan) {
                const poktanData = data.ketuaPoktan;
                $('input[name="profil_Nama"]').val(poktanData.profil_Nama);
                $('input[name="profil_JumlahAnggota"]').val(poktanData.profil_JumlahAnggota);
                $('input[name="profil_TotalLuasTanam"]').val(poktanData.profil_TotalLuasTanam);
                $('input[name="profil_KomoditasMayor"]').val(poktanData.profil_KomoditasMayor);

                $('input[name="agendaBudidaya_KalenderTanam"]').val(poktanData.agendaBudidaya_KalenderTanam);
                $('textarea[name="agendaBudidaya_TantanganUmum"]').val(poktanData.agendaBudidaya_TantanganUmum);
                $('textarea[name="agendaBudidaya_KegiatanKelompok"]').val(poktanData.agendaBudidaya_KegiatanKelompok);

                $('select[name="ketertarikan_SosialisasiProduk"]').val(poktanData.ketertarikan_SosialisasiProduk);
                $('select[name="ketertarikan_DemoPlot"]').val(poktanData.ketertarikan_DemoPlot);
                $('select[name="ketertarikan_ProgramPendampingan"]').val(poktanData.ketertarikan_ProgramPendampingan);
                $('select[name="ketertarikan_SkemaPembelianKolektif"]').val(poktanData.ketertarikan_SkemaPembelianKolektif);

                $('select[name="syaratEkspektasi_TransparansiHarga"]').val(poktanData.syaratEkspektasi_TransparansiHarga);
                $('select[name="syaratEkspektasi_DukunganTeknis"]').val(poktanData.syaratEkspektasi_DukunganTeknis);
                $('select[name="syaratEkspektasi_RewardKelompok"]').val(poktanData.syaratEkspektasi_RewardKelompok);

                $('input[name="aksiAwal_JadwalSosialisasi"]').val(poktanData.aksiAwal_JadwalSosialisasi);
                $('select[name="aksiAwal_LahanDemo"]').val(poktanData.aksiAwal_LahanDemo);
                $('textarea[name="aksiAwal_Anggota"]').val(poktanData.aksiAwal_Anggota);

            } else if (type === 'Farmer Prospect' && data.prospekPetani) {
                const prospekData = data.prospekPetani;
                $('select[name="tantanganUtamaSaatIni"]').val(prospekData.tantanganUtamaSaatIni).trigger('change');
                if(prospekData.tantanganUtamaSaatIni === 'Lainnya') {
                    $('#tantanganUtamaSaatIni_Lainnya').val(prospekData.tantanganUtamaSaatIni_Lainnya).removeClass('d-none');
                }
                $('input[name="dampakHasil_Penurunan"]').val(prospekData.dampakHasil_Penurunan);
                $('input[name="dampakHasil_Area"]').val(prospekData.dampakHasil_Area);

                $('input[name="solusi_ProdukMerek"]').val(prospekData.solusi_ProdukMerek);
                $('input[name="solusi_Dosis"]').val(prospekData.solusi_Dosis);
                $('input[name="solusi_CaraPakai"]').val(prospekData.solusi_CaraPakai);
                $('input[name="solusi_Hasil"]').val(prospekData.solusi_Hasil || 0).trigger('input');
                $('textarea[name="solusi_AlasanPuasTidak"]').val(prospekData.solusi_AlasanPuasTidak);

                $('input[name="rencanaTanamAnggaran_Budget"]').val(prospekData.rencanaTanamAnggaran_Budget);
                $('input[name="rencanaTanamAnggaran_TargetHasil"]').val(prospekData.rencanaTanamAnggaran_TargetHasil);
                $('input[name="rencanaTanamAnggaran_BatasWaktuTanam"]').val(prospekData.rencanaTanamAnggaran_BatasWaktuTanam);

                $('input[name="perilakuPembelian_TokoLangganan"]').val(prospekData.perilakuPembelian_TokoLangganan);
                $('input[name="perilakuPembelian_Pengepul"]').val(prospekData.perilakuPembelian_Pengepul);
                $('input[name="perilakuPembelian_PengambilKeputusan"]').val(prospekData.perilakuPembelian_PengambilKeputusan);

                if(prospekData.minatProgramPembayaranPerpanen == 1) {
                    $('#minatProgramPembayaranPerpanen').prop('checked', true).trigger('change');
                    $('input[name="minatProgramPembayaranPerpanen_KisaranHasil"]').val(prospekData.minatProgramPembayaranPerpanen_KisaranHasil);
                    $('input[name="minatProgramPembayaranPerpanen_FrekuensiPanen"]').val(prospekData.minatProgramPembayaranPerpanen_FrekuensiPanen);
                    $('input[name="minatProgramPembayaranPerpanen_BuktiHasil"]').val(prospekData.minatProgramPembayaranPerpanen_BuktiHasil);
                    $('input[name="minatProgramPembayaranPerpanen_PreferensiTenor"]').val(prospekData.minatProgramPembayaranPerpanen_PreferensiTenor);
                    if(prospekData.minatProgramPembayaranPerpanen_Kesediaan == 1) {
                        $('#minatProgramPembayaranPerpanen_Kesediaan').prop('checked', true);
                    }
                }

                if(prospekData.minatProgramRewardMemberGetMember == 1) {
                    $('#minatProgramRewardMemberGetMember').prop('checked', true).trigger('change');
                    $('input[name="minatProgramRewardMemberGetMember_TopikReward"]').val(prospekData.minatProgramRewardMemberGetMember_TopikReward);
                }

                if(prospekData.kebutuhanPendampinganAgronomis == 1) {
                    $('#kebutuhanPendampinganAgronomis').prop('checked', true).trigger('change');
                    $('input[name="kebutuhanPendampinganAgronomis_Topik"]').val(prospekData.kebutuhanPendampinganAgronomis_Topik);
                    $('input[name="kebutuhanPendampinganAgronomis_WaktuKunjungan"]').val(prospekData.kebutuhanPendampinganAgronomis_WaktuKunjungan);
                }

                $('select[name="kesiapanUjiCobaProdukSti"]').val(prospekData.kesiapanUjiCobaProdukSti).trigger('change');
                if(prospekData.kesiapanUjiCobaProdukSti === 'Tidak berminat') {
                    $('#kesiapanUjiCobaProdukSti_AlasanTidakBerminat').val(prospekData.kesiapanUjiCobaProdukSti_AlasanTidakBerminat).removeClass('d-none');
                }

                $('input[name="komitmenAwal"]').val(prospekData.komitmenAwal);
                if(prospekData.dokumentasi_Photo) {
                    const baseUrl = "{{ asset('/') }}";
                    $('#docPreview').attr('src', baseUrl + prospekData.dokumentasi_Photo).show();
                }
            }
        }

        function restoreStep3Data(data) {
            if (!data || !data.penyelesaianMasalah) return;
            const masalahData = data.penyelesaianMasalah;
            $('#step-3 input[name="deskripsi_SejakKapan"]').val(masalahData.deskripsi_SejakKapan);
            $('#step-3 input[name="deskripsi_TahapanTanaman"]').val(masalahData.deskripsi_TahapanTanaman);
            $('#step-3 input[name="dampak_LuasAreaTerdampak"]').val(masalahData.dampak_LuasAreaTerdampak);
            $('#step-3 input[name="dampak_EstimasiPotensiPenurunanHasil"]').val(masalahData.dampak_EstimasiPotensiPenurunanHasil);

            $('#step-3 textarea[name="riwayatTindakan_ProdukSolusi"]').val(masalahData.riwayatTindakan_ProdukSolusi);
            $('#step-3 input[name="riwayatTindakan_Dosis"]').val(masalahData.riwayatTindakan_Dosis);
            $('#step-3 input[name="riwayatTindakan_Tanggal"]').val(masalahData.riwayatTindakan_Tanggal);

            $('#step-3 select[name="akarDugaan"]').val(masalahData.akarDugaan);
            if(masalahData.akarDugaan === 'Others') {
                $('#step-3 input[name="akarDugaan_Lainnya"]').removeClass('d-none').val(masalahData.akarDugaan_Lainnya);
            }

            $('#step-3 select[name="kebutuhanDukungan"]').val(masalahData.kebutuhanDukungan);

            $('#step-3 input[name="rencanaAksiDisepakati_PaketRekomendasi"]').val(masalahData.rencanaAksiDisepakati_PaketRekomendasi);
            $('#step-3 input[name="rencanaAksiDisepakati_Siapa"]').val(masalahData.rencanaAksiDisepakati_Siapa);

            $('#step-3 input[name="slaPemantauan_Tanggal"]').val(masalahData.slaPemantauan_Tanggal);
            $('#step-3 input[name="slaPemantauan_Jam"]').val(masalahData.slaPemantauan_Jam);

            $('#step-3 select[name="statusTiket"]').val(masalahData.statusTiket);
        }

        function restoreStep4Data(data) {
            if (!data || !data.statistik) return;
            const statData = data.statistik;
            $('#step-4 select[name="curahHujan"]').val(statData.curahHujan);
            $('#step-4 select[name="kejadianEkstrem"]').val(statData.kejadianEkstrem);
            $('#step-4 input[name="tanggal"]').val(statData.tanggal);

            $('#step-4 input[name="harga_TrenHargaPupukBenihPestisida"]').val(statData.harga_TrenHargaPupukBenihPestisida);
            $('#step-4 input[name="harga_HargaJualHasilPanen"]').val(statData.harga_HargaJualHasilPanen);

            $('#step-4 input[name="perubahanPraktikBudidaya_VarietasBaru"]').val(statData.perubahanPraktikBudidaya_VarietasBaru);
            $('#step-4 input[name="perubahanPraktikBudidaya_PerubahanTeknik"]').val(statData.perubahanPraktikBudidaya_PerubahanTeknik);
            $('#step-4 input[name="perubahanPraktikBudidaya_PenggunaanMesin"]').val(statData.perubahanPraktikBudidaya_PenggunaanMesin);

            $('#step-4 input[name="sumberInformasiPetani_Media"]').val(statData.sumberInformasiPetani_Media);
            $('#step-4 input[name="sumberInformasiPetani_TokohLokal"]').val(statData.sumberInformasiPetani_TokohLokal);
            $('#step-4 input[name="sumberInformasiPetani_Penyuluh"]').val(statData.sumberInformasiPetani_Penyuluh);
        }

        function restoreStep5Data(data) {
            if (!data || !data.penutup) return;
            const penutupData = data.penutup;
            $('#step-5 textarea[name="ringkasanKebutuhanSolusi"]').val(penutupData.ringkasanKebutuhanSolusi);

            $('#step-5 input[name="komitmenTindakLanjut_Apa"]').val(penutupData.komitmenTindakLanjut_Apa);
            $('#step-5 input[name="komitmenTindakLanjut_OlehSiapa"]').val(penutupData.komitmenTindakLanjut_OlehSiapa);
            $('#step-5 input[name="komitmenTindakLanjut_KapanTanggal"]').val(penutupData.komitmenTindakLanjut_KapanTanggal);
            $('#step-5 input[name="komitmenTindakLanjut_KapanJam"]').val(penutupData.komitmenTindakLanjut_KapanJam);

            $('#step-5 input[name="jadwalFollowup_Tanggal"]').val(penutupData.jadwalFollowup_Tanggal);
            $('#step-5 input[name="jadwalFollowup_Jam"]').val(penutupData.jadwalFollowup_Jam);
            $('#step-5 select[name="jadwalFollowup_Kanal"]').val(penutupData.jadwalFollowup_Kanal);

            if (penutupData.dokumentasi) {
                const baseUrl = "{{ asset('/') }}";
                $('#closingPreview').attr('src', baseUrl + penutupData.dokumentasi).show();
            }
        }

        function restoreAllStepData(data) {
            if(!data) return;

            // Step 1: General
            $('#jenisKontak').val(data.jenisKontak).trigger('change');
            $('input[name="namaLengkap"]').val(data.namaLengkap);
            $('input[name="jabatan"]').val(data.jabatan);
            $('input[name="noWa"]').val(data.noWa);
            $('input[name="noAlternatif"]').val(data.noAlternatif);
            $('input[name="titikKoordinat"]').val(data.titikKoordinat);
            $('textarea[name="alamatLahanUsaha"]').val(data.alamatLahanUsaha);
            $('input[name="luasLahan"]').val(data.luasLahan);
            $('input[name="musimTanamTanggal"]').val(data.musimTanamTanggal);
            $('input[name="musimTanamPerkiraanPanen"]').val(data.musimTanamPerkiraanPanen);
            $('select[name="musimTanamTahapPertumbuhan"]').val(data.musimTanamTahapPertumbuhan);

            $('#persetujuanPerekamanPanggilan').prop('checked', data.persetujuanPerekamanPanggilan == 1);
            $('#persetujuanPengolahanData').prop('checked', data.persetujuanPengolahanData == 1);

            if (data.evidenceKunjungan) {
                const baseUrl = "{{ asset('/') }}";
                $('#evidencePreview').attr('src', baseUrl + data.evidenceKunjungan).show();
            }

            $('#komoditasUtama').val(data.komoditasUtama).trigger('change');
            if(data.komoditasUtama === 'Lainnya') {
                 $('#komoditasUtamaLainnya').val(data.komoditasUtamaLainnya).removeClass('d-none');
            }

            $('#sistemIrigasi').val(data.sistemIrigasi).trigger('change');
            if(data.sistemIrigasi === 'Lainnya') {
                 $('#sistemIrigasiLainnya').val(data.sistemIrigasiLainnya).removeClass('d-none');
            }

            $('#sumberMengenalSti').val(data.sumberMengenalSti).trigger('change');
             if(data.sumberMengenalSti === 'Lainnya') {
                 $('#sumberMengenalStiLainnya').val(data.sumberMengenalStiLainnya).removeClass('d-none');
            }

            $('#provinsi_name').val(data.provinsi);
            $('#provinsi_kode').val(data.provinsiKode);
            $('#kabupaten_name').val(data.kabupaten);
            $('#kabupaten_kode').val(data.kabupatenKode);
            $('#kecamatan_name').val(data.kecamatan);
            $('#kecamatan_kode').val(data.kecamatanKode);
            $('#desa_name').val(data.desa);
            $('#desa_kode').val(data.desaKode);

            const checkProv = setInterval(() => {
                if($('#provinsiId option').length > 1) {
                    clearInterval(checkProv);
                    $('#provinsiId').val(data.provinsiKode).trigger('change');

                    setTimeout(() => {
                        $.get(`/api/provinces/${data.provinsiKode}/regencies`, function(regencies) {
                            let rOptions = '<option value="">Select Regency</option>';
                            regencies.forEach(r => rOptions += `<option value="${r.id}" data-name="${r.name}">${r.name}</option>`);
                            $('#kabupatenId').html(rOptions).val(data.kabupatenKode).prop('disabled', false);

                            $.get(`/api/regencies/${data.kabupatenKode}/districts`, function(districts) {
                                let dOptions = '<option value="">Select Sub-district</option>';
                                districts.forEach(d => dOptions += `<option value="${d.id}" data-name="${d.name}">${d.name}</option>`);
                                $('#kecamatanId').html(dOptions).val(data.kecamatanKode).prop('disabled', false);

                                $.get(`/api/districts/${data.kecamatanKode}/villages`, function(villages) {
                                    let vOptions = '<option value="">Select Village</option>';
                                    villages.forEach(v => vOptions += `<option value="${v.id}" data-name="${v.name}">${v.name}</option>`);
                                    $('#desaId').html(vOptions).val(data.desaKode).prop('disabled', false);
                                });
                            });
                        });
                    }, 500);
                }
            }, 100);

            // Step 2: Dynamic
            loadSpecificForm(data.jenisKontak);
            restoreStep2Data(data);

            // Step 3-5: Static
            restoreStep3Data(data);
            restoreStep4Data(data);
            restoreStep5Data(data);
        }

        function loadSpecificForm(type) {
            console.log('Loading specific form for:', type);
            // Show Step 2 tab
            $('#step-2-tab-li').removeClass('d-none');

             // Map type to label if desired (optional, but good UX)
             let label = 'Client Specific';
             if(type === 'Farmer Prospect') label = 'Prospek Petani';
             else if(type === 'STI Customer') label = 'Pelanggan STI';
             else if(type === 'Shop/Retailer') label = 'Toko/Pengecer';
             else if(type === 'Partner/Collector') label = 'Mitra/Pengepul';
             else if(type === 'Farmer Group Head') label = 'Ketua Poktan';

             $('#step-2-tab-li a').text(label);

            let html = '<div class="row">';
            if(type === 'STI Customer') {
                html += `
                    <div class="col-md-12 mb-3">
                        <!-- Products Used -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Produk STI yang digunakan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama produk</label>
                                        <input type="text" name="produkStiYangDigunakan_Nama" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Batch (opsional)</label>
                                        <input type="text" name="produkStiYangDigunakan_Batch" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal aplikasi</label>
                                        <input type="date" name="produkStiYangDigunakan_TanggalApplikasi" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Dosis & Cara Pakai</label>
                                        <input type="text" name="produkStiYangDigunakan_DosisCaraPakai" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plant Development -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Perkembangan tanaman (Skala 0–10; catat hari-ke 7/14/21)</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pertumbuhan: <span id="v_pertumbuhan" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="perkembanganTanaman_Pertumbuhan" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_pertumbuhan').text(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hijau Daun: <span id="v_hijau" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="perkembanganTanaman_HijauDaun" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_hijau').text(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Akar: <span id="v_akar" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="perkembanganTanaman_Akar" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_akar').text(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bunga/Polong/Buah: <span id="v_buah" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="perkembanganTanaman_BungaPolongBuah" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_buah').text(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weather -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Kondisi cuaca 7 hari terakhir</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kondisi</label>
                                        <select name="kondisiCuaca" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Kering">Kering</option>
                                            <option value="Normal">Normal</option>
                                            <option value="Basah">Basah</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Catatan curah hujan (mm jika diketahui)</label>
                                        <input type="text" name="kondisiCuaca_Catatan" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Problems -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Masalah yang muncul</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenis</label>
                                        <select name="masalahYangMuncul_Jenis" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="OPT">OPT</option>
                                            <option value="Nutrisi">Nutrisi</option>
                                            <option value="Iklim">Iklim</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Luas terdampak (%)</label>
                                        <input type="number" name="masalahYangMuncul_LuasTerdampak" class="form-control" placeholder="0-100">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keparahan (0-10): <span id="v_keparahan" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="masalahYangMuncul_Keparahan" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_keparahan').text(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Photo Via WA</label>
                                        <input type="file" name="masalahYangMuncul_Photo" class="form-control" accept="image/*" onchange="previewImage(this, '#probPreview')">
                                        <div class="mt-2">
                                            <img id="probPreview" src="#" alt="Preview" style="max-height: 100px; display: none;" class="img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Corrective Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Tindakan korektif yang sudah dilakukan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Apa tindakan yang diambil?</label>
                                        <textarea name="tindakanKorektif_Apa" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kapan?</label>
                                        <input type="date" name="tindakanKorektif_Kapan" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">hasil awal (0-10): <span id="v_tindakan" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="tindakanKorektif_HasilAwal" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_tindakan').text(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assistance -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">
                                <div class="form-check form-switch">
                                    <input class="form-check-input trigger-section" type="checkbox" name="butuhPendampingan" id="butuhPendampingan" data-target="#assistance-details" value="1">
                                    <label class="form-check-label" for="butuhPendampingan">Butuh pendampingan agronomis/kunjungan ?</label>
                                </div>
                            </div>
                            <div class="card-body d-none" id="assistance-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jadwal</label>
                                        <input type="date" name="butuhPendampingan_Jadwal" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" name="butuhPendampingan_Lokasi" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Tujuan</label>
                                        <textarea name="butuhPendampingan_Tujuan" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Results & Satisfaction -->
                         <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Hasil & Kepuasan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Perkiraan hasil (kg/ha)</label>
                                        <input type="number" name="perkiraanHasil" class="form-control" step="0.01">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Rencana panen</label>
                                        <input type="date" name="rencanaPanen" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kepuasan terhadap produk/layanan (0-10): <span id="v_puas" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="kepuasanTerhadapProdukLayanan_Nilai" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#v_puas').text(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Alasan</label>
                                        <input type="text" name="kepuasanTerhadapProdukLayanan_Alasan" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="minatIkutLanjutProgramReward" id="minatIkutLanjutProgramReward" value="1">
                                            <label class="form-check-label" for="minatIkutLanjutProgramReward">Minat ikut/lanjut Program Reward ?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Member Get Member -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">
                                <div class="form-check form-switch">
                                    <input class="form-check-input trigger-section" type="checkbox" name="memberGetMember" id="memberGetMember" data-target="#mgm-details" value="1">
                                    <label class="form-check-label" for="memberGetMember">Member Get Member?</label>
                                </div>
                            </div>
                            <div class="card-body d-none" id="mgm-details">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nama Referral</label>
                                        <input type="text" name="memberGetMember_Referal" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Next Step -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Next Step</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tindak lanjut yang disepakati</label>
                                        <input type="text" name="nextStep_TindakLanjut" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Waktu Follow-up</label>
                                        <input type="date" name="nextStep_WaktuFollowup" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if(type === 'Shop/Retailer') {
                html += `
                    <div class="col-md-12 mb-3">
                        <!-- Profile -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Profil Toko</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Toko</label>
                                        <input type="text" name="profil_NamaToko" class="form-control" placeholder="Nama Toko">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" name="profil_Alamat" class="form-control" placeholder="Alamat">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kanal Penjualan</label>
                                        <select name="profil_KanalPenjualan" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Offline">Offline</option>
                                            <option value="Online">Online</option>
                                            <option value="Both">Both</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Volume penjualan bulanan</label>
                                        <select name="profil_VolumePenjualanBulanan" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="< 50 Juta">< 50 Juta</option>
                                            <option value="50 - 100 Juta">51 - 100 Juta</option>
                                            <option value="101 - 250 Juta">101 - 250 Juta</option>
                                            <option value="> 250 Juta">> 250 Juta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Merek yang dijual</label>
                                        <textarea name="profil_MerekYangDijual" class="form-control" rows="1" placeholder="Merek A, Merek B..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Needs & Interests -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Kebutuhan & ketertarikan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Produk STI yang diminati</label>
                                        <textarea name="kebutuhanKetertarikan_ProdukSti" class="form-control" rows="2" placeholder="Which products and why?"></textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Margin yang diharapkan</label>
                                        <input type="text" name="kebutuhanKetertarikan_Margin" class="form-control" placeholder="e.g. 15%">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Syarat pembayaran</label>
                                        <input type="text" name="kebutuhanKetertarikan_SyaratPembayaran" class="form-control" placeholder="e.g. Tempo 30 days">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Dukungan promosi yang diinginkan</label>
                                        <input type="text" name="kebutuhanKetertarikan_DukunganPromosi" class="form-control" placeholder="e.g. Banners, Spanduk">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Program Availability -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Ketersediaan Program</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Display materi</label>
                                        <select name="kesediaanProgram_DisplayMateri" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Stok awal</label>
                                        <input type="text" name="kesediaanProgram_StokAwal" class="form-control" placeholder="e.g. 5 Cartons">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Demo plot</label>
                                        <select name="kesediaanProgram_DemoPlot" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program poin</label>
                                        <select name="kesediaanProgram_ProgramPoin" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Partnership Plan -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Rencana kerjasama</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch px-4">
                                            <input class="form-check-input trigger-section" type="checkbox" name="rencanaKerjasama_POAwal" id="rencanaKerjasama_POAwal" data-target="#po-awal-details" value="1">
                                            <label class="form-check-label" for="rencanaKerjasama_POAwal">PO Awal</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3 d-none" id="po-awal-details">
                                        <label class="form-label">Estimasi (Rp / Qty)</label>
                                        <input type="text" name="rencanaKerjasama_POAwal_Estimasi" class="form-control" placeholder="Estimasi">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jadwal pelatihan tim toko</label>
                                        <input type="date" name="rencanaKerjasama_JadwalPelatihan" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Target 3 Bulan</label>
                                        <input type="text" name="rencanaKerjasama_TargetTigaBulan" class="form-control" placeholder="Target">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Member Get Member -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Member Get Member</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" name="memberGetMember_Nama" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kontak</label>
                                        <input type="text" name="memberGetMember_Kontak" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                `;
            } else if(type === 'Farmer Prospect') {
                html += `
                    <div class="col-md-12 mb-3">
                        <!-- Main Challenges -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Tantangan Utama Saat Ini</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tantangan Utama</label>
                                        <select name="tantanganUtamaSaatIni" class="form-select trigger-other" data-target="#tantanganUtamaSaatIni_Lainnya">
                                            <option value="">Pilihan</option>
                                            <option value="Hama/Penyakit">Hama/Penyakit</option>
                                            <option value="Kekurangan Unsur Hara">Kekurangan Unsur Hara</option>
                                            <option value="Kualitas Bibit">Kualitas Bibit</option>
                                            <option value="Biaya Input">Biaya Input</option>
                                            <option value="Keterbatasan Air/Curah Hujan">Keterbatasan Air/Curah Hujan</option>
                                            <option value="Gulma">Gulma</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <input type="text" name="tantanganUtamaSaatIni_Lainnya" id="tantanganUtamaSaatIni_Lainnya" class="form-control mt-2 d-none" placeholder="Masukkan lainnya">
                                    </div> 
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Penurunan hasil estimasi (%, Angka kg/ha)</label>
                                        <input type="number" name="dampakHasil_Penurunan" class="form-control" placeholder="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Area terdampak (% lahan)</label>
                                        <input type="number" name="dampakHasil_Area" class="form-control" step="0.01" placeholder="0.0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Solution -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Solusi yang sudah dicoba</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Merek produk</label>
                                        <input type="text" name="solusi_ProdukMerek" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Dosis</label>
                                        <input type="text" name="solusi_Dosis" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cara penggunaan</label>
                                        <input type="text" name="solusi_CaraPakai" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hasil (Skala 0–10): <span id="solusi_Hasil_val" class="fw-bold text-primary">0</span></label>
                                        <input type="range" name="solusi_Hasil" class="form-range" min="0" max="10" step="1" value="0" oninput="$('#solusi_Hasil_val').text(this.value)">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Alasan puas / tidak puas</label>
                                        <textarea name="solusi_AlasanPuasTidak" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Planting Plan Budget -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Rencana Tanam & Anggaran</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Budget input/ha per musim (Rp)</label>
                                        <input type="number" name="rencanaTanamAnggaran_Budget" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Target hasil (kg/ha) (Rp)</label>
                                        <input type="number" name="rencanaTanamAnggaran_TargetHasil" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Batas waktu panen</label>
                                        <input type="date" name="rencanaTanamAnggaran_BatasWaktuTanam" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Behavior -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Perilaku pembelian</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Toko langganan (Nama/Alamat/Kontak)</label>
                                        <input type="text" name="perilakuPembelian_TokoLangganan" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Pengepul (Nama/Kontak)</label>
                                        <input type="text" name="perilakuPembelian_Pengepul" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Pengambil keputusan (Siapa saja & perannya)</label>
                                        <input type="text" name="perilakuPembelian_PengambilKeputusan" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Harvest Payment Program -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">
                                <div class="form-check form-switch">
                                    <input class="form-check-input trigger-section" type="checkbox" name="minatProgramPembayaranPerpanen" id="minatProgramPembayaranPerpanen" data-target="#harvest-program-details" value="1">
                                    <label class="form-check-label" for="minatProgramPembayaranPerpanen">Minat Program Pembayaran Per Panen</label>
                                </div>
                            </div>
                            <div class="card-body d-none" id="harvest-program-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kisaran hasil/panen terakhir (kg/ha)</label>
                                        <input type="number" name="minatProgramPembayaranPerpanen_KisaranHasil" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Frekuensi panen</label>
                                        <input type="number" name="minatProgramPembayaranPerpanen_FrekuensiPanen" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bukti hasil</label>
                                        <input type="text" name="minatProgramPembayaranPerpanen_BuktiHasil" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Preferensi tenor/cara bayar</label>
                                        <input type="text" name="minatProgramPembayaranPerpanen_PreferensiTenor" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="minatProgramPembayaranPerpanen_Kesediaan" id="minatProgramPembayaranPerpanen_Kesediaan" value="1">
                                            <label class="form-check-label" for="minatProgramPembayaranPerpanen_Kesediaan">Kesediaan mengikuti verifikasi lapangan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Member Reward Program -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">
                                <div class="form-check form-switch">
                                    <input class="form-check-input trigger-section" type="checkbox" name="minatProgramRewardMemberGetMember" id="minatProgramRewardMemberGetMember" data-target="#member-reward-details" value="1">
                                    <label class="form-check-label" for="minatProgramRewardMemberGetMember">Minat Program Reward & Member Get Member ?</label>
                                </div>
                            </div>
                            <div class="card-body d-none" id="member-reward-details">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Topik reward yang menarik</label>
                                        <input type="text" name="minatProgramRewardMemberGetMember_TopikReward" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agronomic Assistance -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">
                                <div class="form-check form-switch">
                                    <input class="form-check-input trigger-section" type="checkbox" name="kebutuhanPendampinganAgronomis" id="kebutuhanPendampinganAgronomis" data-target="#agronomic-details" value="1">
                                    <label class="form-check-label" for="kebutuhanPendampinganAgronomis">Kebutuhan pendampingan agronomis ?</label>
                                </div>
                            </div>
                            <div class="card-body d-none" id="agronomic-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Topik</label>
                                        <input type="text" name="kebutuhanPendampinganAgronomis_Topik" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Waktu kunjungan yang diinginkan</label>
                                        <input type="date" name="kebutuhanPendampinganAgronomis_WaktuKunjungan" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Closing & Photo -->
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Kesiapan uji coba produk STI</label>
                                <select name="kesiapanUjiCobaProdukSti" class="form-select trigger-other" data-target="#kesiapanUjiCobaProdukSti_AlasanTidakBerminat" data-inv-logic="true">
                                    <option value="">Pilihan</option>
                                    <option value="Siap sekarang">Siap sekarang</option>
                                    <option value="Butuh info tambahan">Butuh info tambahan</option>
                                    <option value="Tidak berminat">Tidak berminat</option>
                                </select>
                                <input type="text" name="kesiapanUjiCobaProdukSti_AlasanTidakBerminat" id="kesiapanUjiCobaProdukSti_AlasanTidakBerminat" class="form-control mt-2 d-none" placeholder="Alasan tidak berminat">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Komitmen awal</label>
                                <input type="text" name="komitmenAwal" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Bukti hasil (foto/nota)</label>
                                <input type="file" name="dokumentasi_Photo" class="form-control" accept="image/*" onchange="previewImage(this, '#docPreview')">
                                <div class="mt-2">
                                     <img id="docPreview" src="#" alt="Preview" style="max-height: 150px; display: none;" class="img-thumbnail">
                                </div>
                            </div>
                        </div>

                    </div>
                `;
            } else if(type === 'Partner/Collector') {
                html += `
                    <div class="col-md-12 mb-3">
                        <!-- Profile -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Profile Usaha</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Usaha</label>
                                        <input type="text" name="profil_NamaUsaha" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Komoditas utama</label>
                                        <input type="text" name="profil_KomoditasUtama" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wilayah jangkauan</label>
                                        <input type="text" name="profil_WilayahJangkauan" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Musiman</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="profil_Musiman" id="profil_Musiman" value="1">
                                            <label class="form-check-label" for="profil_Musiman">Musiman? (Ya/Tidak)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Needs -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Kebutuhan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Konsistensi pasokan</label>
                                        <select name="kebutuhan_KonsistensiPasokan" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kualitas</label>
                                        <select name="kebutuhan_Kualitas" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Export">Export</option>
                                            <option value="Premium">Premium</option>
                                            <option value="Standard">Standard</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Dukungan budidaya untuk petani binaan</label>
                                        <textarea name="kebutuhan_DukunganBudidaya" class="form-control" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Collaboration Model -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Model kerjasama</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Skema kemitraan dengan STI</label>
                                        <select name="modelKerjasama_SkemaKemitraan" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Contract Farming">Contract Farming</option>
                                            <option value="Trading">Trading</option>
                                            <option value="Joint Operation">Joint Operation</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Keterlibatan program</label>
                                        <select name="modelKerjasama_KeterlibatanProgram" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Active">Active</option>
                                            <option value="Passive">Passive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Dukungan logistik dan pendidikan</label>
                                        <textarea name="modelKerjasama_DukunganLogistikEdukasi" class="form-control" rows="1"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="potensiIntegrasiDataPanen" id="potensiIntegrasiDataPanen" value="1">
                                            <label class="form-check-label" for="potensiIntegrasiDataPanen">Potensi integrasi data panen (Y/N) untuk verifikasi program</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Initial Commitment -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Komitmen awal</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal pertemuan selanjutnya</label>
                                        <input type="date" name="komitmenAwal_PertemuanSelanjutnya" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PIC Teknis</label>
                                        <input type="text" name="komitmenAwal_PicTeknis" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Data yang dibutuhkan</label>
                                        <textarea name="komitmenAwal_DataYangDibutuhkan" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if(type === 'Farmer Group Head') {
                html += `
                    <div class="col-md-12 mb-3">
                        <!-- Profile -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Profil kelompok</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama kelompok</label>
                                        <input type="text" name="profil_Nama" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jumlah anggota</label>
                                        <input type="number" name="profil_JumlahAnggota" class="form-control" placeholder="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Luas tanam (Ha)</label>
                                        <input type="text" name="profil_TotalLuasTanam" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Komoditas mayor</label>
                                        <input type="text" name="profil_KomoditasMayor" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cultivation Agenda -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Agenda budidaya</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Kalender tanam</label>
                                        <input type="date" name="agendaBudidaya_KalenderTanam" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Tantangan umum</label>
                                        <textarea name="agendaBudidaya_TantanganUmum" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Kegiatan kelompok</label>
                                        <textarea name="agendaBudidaya_KegiatanKelompok" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Interest -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Ketertarikan</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sosialisasi produk</label>
                                        <select name="ketertarikan_SosialisasiProduk" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Tertarik">Tertarik</option>
                                            <option value="Mungkin">Mungkin</option>
                                            <option value="Tidak Tertarik">Tidak Tertarik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Demo Plot</label>
                                        <select name="ketertarikan_DemoPlot" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Tertarik">Tertarik</option>
                                            <option value="Mungkin">Mungkin</option>
                                            <option value="Tidak Tertarik">Tidak Tertarik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program pendampingan</label>
                                        <select name="ketertarikan_ProgramPendampingan" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Tertarik">Tertarik</option>
                                            <option value="Mungkin">Mungkin</option>
                                            <option value="Tidak Tertarik">Tidak Tertarik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Skema pembelian kolektif</label>
                                        <select name="ketertarikan_SkemaPembelianKolektif" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Tertarik">Tertarik</option>
                                            <option value="Mungkin">Mungkin</option>
                                            <option value="Tidak Tertarik">Tidak Tertarik</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Terms & Expectations -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Syarat & ekspektasi</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Transparansi harga</label>
                                        <select name="syaratEkspektasi_TransparansiHarga" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Sangat Penting">Sangat Penting</option>
                                            <option value="Penting">Penting</option>
                                            <option value="Tidak Penting">Tidak Penting</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Dukungan teknis</label>
                                        <select name="syaratEkspektasi_DukunganTeknis" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Sangat Penting">Sangat Penting</option>
                                            <option value="Penting">Penting</option>
                                            <option value="Tidak Penting">Tidak Penting</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Reward kelompok</label>
                                        <select name="syaratEkspektasi_RewardKelompok" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Sangat Penting">Sangat Penting</option>
                                            <option value="Penting">Penting</option>
                                            <option value="Tidak Penting">Tidak Penting</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Initial Action -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Aksi awal</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jadwal sosialisasi</label>
                                        <input type="date" name="aksiAwal_JadwalSosialisasi" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lahan demo</label>
                                        <select name="aksiAwal_LahanDemo" class="form-select">
                                            <option value="">Pilihan</option>
                                            <option value="Tersedia">Tersedia</option>
                                            <option value="Tidak Tersedia">Tidak Tersedia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Daftar 10 calon anggota awal</label>
                                        <textarea name="aksiAwal_Anggota" class="form-control" rows="5" placeholder="1. Nama - No. Telp&#10,2. Nama - No. Telp..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            html += '</div>';
            $('#dynamic-step-2-content').html(html);

            // Re-populate Step 2 data if available
            if (loadedSurveyData) {
                restoreStep2Data(loadedSurveyData);
            }
        }

        // Dynamic Event Listeners for "Other" inputs
        $(document).on('change', '.trigger-other', function() {
            const target = $($(this).data('target'));
            const isInvLogic = $(this).data('inv-logic'); // For "Not Ready" logic
            const val = $(this).val();
            
            let showCondition = false;
            if(isInvLogic) {
                // Trigger if value is "Not Ready" or "Tidak berminat"
                showCondition = (val === 'Not Ready' || val === 'Tidak berminat');
            } else {
                // Trigger if value is "Lainnya", "Others", or "Lainnya/Other"
                showCondition = (val === 'Lainnya' || val === 'Others' || val === 'Lainnya/Other');
            }

            if(showCondition) {
                target.removeClass('d-none').prop('required', true);
            } else {
                target.addClass('d-none').prop('required', false).val('');
            }
        });

        // Dynamic Event Listeners for Sections (Switches)
        $(document).on('change', '.trigger-section', function() {
            const target = $($(this).data('target'));
            if($(this).is(':checked')) {
                target.removeClass('d-none');
                // Make inputs inside required if needed, or just rely on backend validation/logic
                target.find('input, select, textarea').not('.exclude-req').prop('disabled', false);
            } else {
                target.addClass('d-none');
                target.find('input, select, textarea').prop('disabled', true);
            }
        });

        @isset($prefill)
            $('input[name="namaLengkap"]').val("{{ $prefill->namaLengkap }}");
            $('input[name="jabatan"]').val("{{ $prefill->jabatan }}");
            $('input[name="noWa"]').val("{{ $prefill->noWa }}");
            $('input[name="noAlternatif"]').val("{{ $prefill->noAlternatif }}");
            $('textarea[name="alamatLahanUsaha"]').val("{{ $prefill->alamatLahanUsaha }}");
            $('input[name="titikKoordinat"]').val("{{ $prefill->titikKoordinat }}");
            $('input[name="luasLahan"]').val("{{ $prefill->luasLahan }}");
            $('#jenisKontak').val("{{ $prefill->jenisKontak }}").trigger('change');
        @endisset
    });

    function previewImage(input, targetSelector) {
        const previewTarget = targetSelector ? $(targetSelector) : $('#evidencePreview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewTarget.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

<style>
    .nav-pills .nav-link {
        cursor: pointer;
        border: 1px solid #eee;
        margin: 0 5px;
        background: #f8f9fa;
        color: #666;
    }
    .nav-pills .nav-link.active {
        background: #00861fff !important;
        color: #fff !important;
    }
    .nav-pills .nav-link.disabled {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
