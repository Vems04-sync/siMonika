<!-- Add/Edit Application Modal -->
<div class="modal fade" id="appModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Aplikasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('aplikasi.store') }}" method="POST" id="appForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Aplikasi:</label>
                            <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="opd" class="form-label">OPD:</label>
                            <input type="text" id="opd" name="opd" class="form-control" value="{{ old('opd') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="uraian" class="form-label">Uraian:</label>
                        <textarea id="uraian" name="uraian" class="form-control">{{ old('uraian') }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan:</label>
                            <input type="date" id="tahun_pembuatan" name="tahun_pembuatan" class="form-control" value="{{ old('tahun_pembuatan') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="jenis" class="form-label">Jenis:</label>
                            <select id="jenis" name="jenis" class="form-select">
                                <option value="Layanan Publik" {{ old('jenis') == 'Layanan Publik' ? 'selected' : '' }}>Layanan Publik</option>
                                <option value="Administrasi Pemerintahan" {{ old('jenis') == 'Administrasi Pemerintahan' ? 'selected' : '' }}>Administrasi Pemerintahan</option>
                                <option value="Fungsi Tertentu" {{ old('jenis') == 'Fungsi Tertentu' ? 'selected' : '' }}>Fungsi Tertentu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="basis_aplikasi" class="form-label">Basis Aplikasi:</label>
                            <select id="basis_aplikasi" name="basis_aplikasi" class="form-select">
                                <option value="Mobile" {{ old('basis_aplikasi') == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="Website" {{ old('basis_aplikasi') == 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="Desktop" {{ old('basis_aplikasi') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bahasa_framework" class="form-label">Bahasa Pemrograman/Framework:</label>
                            <input type="text" id="bahasa_framework" name="bahasa_framework" class="form-control" value="{{ old('bahasa_framework') }}"required >
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="database" class="form-label">Database:</label>
                            <select id="database" name="database" class="form-select">
                                <option value="MySQL" {{ old('database') == 'MySQL' ? 'selected' : '' }}>MySQL</option>
                                <option value="PostgreSQL" {{ old('database') == 'PostgreSQL' ? 'selected' : '' }}>PostgreSQL</option>
                                <option value="MongoDB" {{ old('database') == 'MongoDB' ? 'selected' : '' }}>MongoDB</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pengembang" class="form-label">Pengembang:</label>
                            <select id="pengembang" name="pengembang" class="form-select">
                                <option value="Internal OPD" {{ old('pengembang') == 'Internal OPD' ? 'selected' : '' }}>Internal OPD</option>
                                <option value="Diskominfo" {{ old('pengembang') == 'Diskominfo' ? 'selected' : '' }}>Diskominfo</option>
                                <option value="Vendor" {{ old('pengembang') == 'Vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="lokasi_server" class="form-label">Lokasi Server:</label>
                            <select id="lokasi_server" name="lokasi_server" class="form-select">
                                <option value="Server Diskominfo" {{ old('lokasi_server') == 'Server Diskominfo' ? 'selected' : '' }}>Server Diskominfo</option>
                                <option value="Server Internal OPD" {{ old('lokasi_server') == 'Server Internal OPD' ? 'selected' : '' }}>Server Internal OPD</option>
                                <option value="PDN" {{ old('lokasi_server') == 'PDN' ? 'selected' : '' }}>PDN</option>
                                <option value="Vendor" {{ old('lokasi_server') == 'Vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status_pemakaian" class="form-label">Status Pemakaian:</label>
                            <select id="status_pemakaian" name="status_pemakaian" class="form-select">
                                <option value="Aktif" {{ old('status_pemakaian') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status_pemakaian') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
