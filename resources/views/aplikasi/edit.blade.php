<!-- Edit Application Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Aplikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_nama" class="form-label">Nama Aplikasi</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_opd" class="form-label">OPD</label>
                            <input type="text" class="form-control" id="edit_opd" name="opd" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_uraian" class="form-label">Uraian</label>
                        <textarea class="form-control" id="edit_uraian" name="uraian"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                            <input type="date" class="form-control" id="edit_tahun_pembuatan" name="tahun_pembuatan">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_jenis" class="form-label">Jenis</label>
                            <select class="form-select" id="edit_jenis" name="jenis" required>
                                <option value="Layanan Publik">Layanan Publik</option>
                                <option value="Administrasi Pemerintahan">Administrasi Pemerintahan</option>
                                <option value="Fungsi Tertentu">Fungsi Tertentu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_basis_aplikasi" class="form-label">Basis Aplikasi</label>
                            <select class="form-select" id="edit_basis_aplikasi" name="basis_aplikasi" required>
                                <option value="Mobile">Mobile</option>
                                <option value="Website">Website</option>
                                <option value="Desktop">Desktop</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_bahasa_framework" class="form-label">Bahasa Pemrograman/Framework</label>
                            <input type="text" class="form-control" id="edit_bahasa_framework" name="bahasa_framework" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_database" class="form-label">Database</label>
                            <select class="form-select" id="edit_database" name="database" required>
                                <option value="MySQL">MySQL</option>
                                <option value="PostgreSQL">PostgreSQL</option>
                                <option value="MongoDB">MongoDB</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pengembang" class="form-label">Pengembang</label>
                            <select class="form-select" id="edit_pengembang" name="pengembang" required>
                                <option value="Internal OPD">Internal OPD</option>
                                <option value="Diskominfo">Diskominfo</option>
                                <option value="Vendor">Vendor</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_lokasi_server" class="form-label">Lokasi Server</label>
                            <select class="form-select" id="edit_lokasi_server" name="lokasi_server" required>
                                <option value="Server Diskominfo">Server Diskominfo</option>
                                <option value="Server Internal OPD">Server Internal OPD</option>
                                <option value="PDN">PDN</option>
                                <option value="Vendor">Vendor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_status_pemakaian" class="form-label">Status Pemakaian</label>
                            <select class="form-select" id="edit_status_pemakaian" name="status_pemakaian" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
