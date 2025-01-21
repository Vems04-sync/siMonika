<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplikasi - siMonika</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    @include('templates/sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-0">
            <div>
                <h2 class="mb-0">Kelola Aplikasi</h2>
                <p class="text-muted">Manajemen data aplikasi dan informasinya</p>
            </div>
            <div class="button-action">
                <!-- Toggle View Button -->
                <button class="btn btn-outline-secondary me-2" id="toggleView">
                    <i class="bi bi-grid"></i>
                    <span>Ubah Tampilan</span>
                </button>
                <!-- Button Export Excel -->
                <a href="{{ route('aplikasi.export') }}" class="btn btn-outline-primary">
                    <i class="bi bi-download"></i>
                    <span class="me-2">Export</span>
                </a>
                <!-- Button Tambah Aplikasi -->
                <button class="btn btn-primary" onclick="addApp()">
                    <i class="bi bi-plus-lg"></i>
                    <span class="me-2">Tambah Aplikasi</span>
                </button>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="unused">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="jenisFilter">
                            <option value="">Semua Jenis</option>
                            @foreach ($aplikasis->pluck('jenis')->unique() as $jenis)
                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="basisFilter">
                            <option value="">Semua Basis Aplikasi</option>
                            <option value="Website">Website</option>
                            <option value="Desktop">Desktop</option>
                            <option value="Mobile">Mobile</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="bahasaFilter">
                            <option value="">Semua Bahasa/Framework</option>
                            @foreach ($aplikasis->pluck('bahasa_framework')->unique() as $bahasa)
                                <option value="{{ $bahasa }}">{{ $bahasa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="databaseFilter">
                            <option value="">Semua Database</option>
                            @foreach ($aplikasis->pluck('database')->unique() as $database)
                                <option value="{{ $database }}">{{ $database }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="pengembangFilter">
                            <option value="">Semua Pengembang</option>
                            @foreach ($aplikasis->pluck('pengembang')->unique() as $pengembang)
                                <option value="{{ $pengembang }}">{{ $pengembang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="lokasiFilter">
                            <option value="">Semua Lokasi Server</option>
                            @foreach ($aplikasis->pluck('lokasi_server')->unique() as $lokasi)
                                <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari aplikasi..." id="searchApp">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card View -->
        <div class="row g-4" id="cardView">
            @foreach ($aplikasis as $aplikasi)
                <div class="col-md-6 col-lg-4 app-card"
                    data-status="{{ $aplikasi->status_pemakaian == 'Aktif' ? 'active' : 'unused' }}"
                    data-jenis="{{ $aplikasi->jenis }}" data-basis="{{ $aplikasi->basis_aplikasi }}"
                    data-bahasa="{{ $aplikasi->bahasa_framework }}" data-database="{{ $aplikasi->database }}"
                    data-pengembang="{{ $aplikasi->pengembang }}" data-lokasi="{{ $aplikasi->lokasi_server }}">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="app-icon me-3">
                                        <i class="bi bi-window"></i>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $aplikasi->nama }}</h5>
                                </div>
                                <div class="status-badge {{ $aplikasi->status_pemakaian == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $aplikasi->status_pemakaian }}
                                </div>
                            </div>

                            <div class="app-info mb-3">
                                <p class="mb-2"><i class="bi bi-calendar me-2"></i>Tahun Pembuatan: {{ $aplikasi->tahun_pembuatan }}</p>
                                <p class="mb-2"><i class="bi bi-tag me-2"></i>Jenis: {{ $aplikasi->jenis }}</p>
                                <p class="mb-2"><i class="bi bi-device-mobile me-2"></i>Basis Aplikasi: {{ $aplikasi->basis_aplikasi }}</p>
                                <p class="mb-2"><i class="bi bi-code-slash me-2"></i>Bahasa Framework: {{ $aplikasi->bahasa_framework }}</p>
                            </div>

                            <p class="card-text text-muted">{{ Str::limit($aplikasi->uraian, 100) }}</p>

                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-info btn-sm" onclick="showDetail('{{ $aplikasi->id_aplikasi }}')">
                                    <i class="bi bi-eye">Detail</i>
                                </button>
                                <div>
                                    <a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="editApp('{{ $aplikasi->id_aplikasi }}')">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteApp('{{ $aplikasi->id_aplikasi }}')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View -->
        <div class="table-responsive" id="tableView" style="display: none;">
            <div class="shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">OPD</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tahun Pembuatan</th>
                                <th scope="col">Jenis</th>
                                <th scope="col">Basis Aplikasi</th>
                                <th scope="col">Bahasa/Framework</th>
                                <th scope="col">Database</th>
                                <th scope="col">Pengembang</th>
                                <th scope="col">Lokasi Server</th>
                                <th scope="col" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aplikasis as $index => $aplikasi)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $aplikasi->nama }}</td>
                                    <td>{{ $aplikasi->opd }}</td>
                                    <td>
                                        <span class="badge {{ $aplikasi->status_pemakaian == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $aplikasi->status_pemakaian }}
                                        </span>
                                    </td>
                                    <td>{{ $aplikasi->tahun_pembuatan }}</td>
                                    <td>{{ $aplikasi->jenis }}</td>
                                    <td>
                                        @if ($aplikasi->basis_aplikasi === 'Desktop')
                                            <i class="bi bi-laptop me-1"></i>
                                        @elseif ($aplikasi->basis_aplikasi === 'Mobile')
                                            <i class="bi bi-phone me-1"></i>
                                        @elseif ($aplikasi->basis_aplikasi === 'Website')
                                            <i class="bi bi-browser-chrome me-1"></i>
                                        @endif
                                        {{ $aplikasi->basis_aplikasi }}
                                    </td>
                                    <td>{{ $aplikasi->bahasa_framework }}</td>
                                    <td>{{ $aplikasi->database }}</td>
                                    <td>{{ $aplikasi->pengembang }}</td>
                                    <td>{{ $aplikasi->lokasi_server }}</td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-info btn-sm" onclick="showDetail({{ $aplikasi->id_aplikasi }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm" onclick="editApp('{{ $aplikasi->id_aplikasi }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteApp('{{ $aplikasi->id_aplikasi }}')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination akan ditambahkan di sini oleh JavaScript -->
        </div>

        @include('aplikasi/create')
    </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailAplikasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Aplikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Aplikasi</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%">Nama</td>
                                    <td id="detail-nama"></td>
                                </tr>
                                <tr>
                                    <td>OPD</td>
                                    <td id="detail-opd"></td>
                                </tr>
                                <tr>
                                    <td>Uraian</td>
                                    <td id="detail-uraian"></td>
                                </tr>
                                <tr>
                                    <td>Tahun Pembuatan</td>
                                    <td id="detail-tahun"></td>
                                </tr>
                                <tr>
                                    <td>Jenis</td>
                                    <td id="detail-jenis"></td>
                                </tr>
                                <tr>
                                    <td>Basis Aplikasi</td>
                                    <td id="detail-basis"></td>
                                </tr>
                                <tr>
                                    <td>Bahasa/Framework</td>
                                    <td id="detail-bahasa"></td>
                                </tr>
                                <tr>
                                    <td>Database</td>
                                    <td id="detail-database"></td>
                                </tr>
                                <tr>
                                    <td>Pengembang</td>
                                    <td id="detail-pengembang"></td>
                                </tr>
                                <tr>
                                    <td>Lokasi Server</td>
                                    <td id="detail-server"></td>
                                </tr>
                                <tr>
                                    <td>Status Pemakaian</td>
                                    <td id="detail-status"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Atribut Tambahan</h6>
                            <div id="atribut-tambahan-content">
                                <!-- Konten atribut akan dimuat di sini -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
                                <input type="text" class="form-control" id="edit_tahun_pembuatan" name="tahun_pembuatan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_jenis" class="form-label">Jenis</label>
                                <input type="text" class="form-control" id="edit_jenis" name="jenis" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_basis_aplikasi" class="form-label">Basis Aplikasi</label>
                                <input type="text" class="form-control" id="edit_basis_aplikasi" name="basis_aplikasi" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_bahasa_framework" class="form-label">Bahasa/Framework</label>
                                <input type="text" class="form-control" id="edit_bahasa_framework" name="bahasa_framework" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_database" class="form-label">Database</label>
                                <input type="text" class="form-control" id="edit_database" name="database" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_pengembang" class="form-label">Pengembang</label>
                                <input type="text" class="form-control" id="edit_pengembang" name="pengembang" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_lokasi_server" class="form-label">Lokasi Server</label>
                                <input type="text" class="form-control" id="edit_lokasi_server" name="lokasi_server" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_status_pemakaian" class="form-label">Status Pemakaian</label>
                                <select class="form-select" id="edit_status_pemakaian" name="status_pemakaian" required>
                                    <option value="">Pilih status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('aplikasi.edit')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Script aplikasi -->
    <script src="{{ asset('js/aplikasi/index.js') }}"></script>

    <!-- Tambahkan ini untuk flash message dari session -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.success("{{ session('success') }}", "Berhasil");
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.error("{{ session('error') }}", "Error");
            });
        </script>
    @endif

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function showDetail(id) {
        $.ajax({
            url: `/aplikasi/${id}/detail`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Response:', response); // Debug log
                
                if (response.success) {
                    const app = response.data;
                    
                    // Populate modal fields
                    $('#detail-nama').text(app.nama || '-');
                    $('#detail-opd').text(app.opd || '-');
                    $('#detail-uraian').text(app.uraian || '-');
                    $('#detail-tahun').text(app.tahun_pembuatan || '-');
                    $('#detail-jenis').text(app.jenis || '-');
                    $('#detail-basis').text(app.basis_aplikasi || '-');
                    $('#detail-bahasa').text(app.bahasa_framework || '-');
                    $('#detail-database').text(app.database || '-');
                    $('#detail-pengembang').text(app.pengembang || '-');
                    $('#detail-server').text(app.lokasi_server || '-');
                    
                    // Tambahkan kelas untuk styling status
                    const statusClass = app.status_pemakaian === 'Aktif' ? 'text-success' : 'text-danger';
                    $('#detail-status').html(`<span class="${statusClass}">${app.status_pemakaian || '-'}</span>`);

                    // Build atribut tambahan table dengan styling yang lebih baik
                    let atributContent = '<div class="table-responsive">';
                    atributContent += '<table class="table table-bordered table-hover">';
                    atributContent += '<thead class="table-light"><tr><th>Nama Atribut</th><th>Nilai</th></tr></thead>';
                    atributContent += '<tbody>';
                    
                    if (app.atribut_tambahans && app.atribut_tambahans.length > 0) {
                        app.atribut_tambahans.forEach(atribut => {
                            const nilai = atribut.pivot?.nilai_atribut || '-';
                            atributContent += `
                                <tr>
                                    <td class="fw-medium">${atribut.nama_atribut}</td>
                                    <td>${nilai}</td>
                                </tr>`;
                        });
                    } else {
                        atributContent += `
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada atribut tambahan
                                </td>
                            </tr>`;
                    }
                    
                    atributContent += '</tbody></table></div>';
                    $('#atribut-tambahan-content').html(atributContent);

                    // Show modal
                    $('#detailAplikasiModal').modal('show');
                } else {
                    toastr.error(response.message || 'Gagal memuat detail aplikasi');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', {xhr, status, error}); // Debug log
                toastr.error('Terjadi kesalahan saat memuat data');
            }
        });
    }

    // Event handler untuk tombol detail
    $(document).on('click', '.btn-info', function() {
        const id = $(this).closest('tr').find('td:first').text();
        showDetail(id);
    });

    function editApp(id) {
        $.ajax({
            url: `/aplikasi/${id}/edit`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const app = response.aplikasi;
                    
                    // Set form action URL with the correct id
                    $('#editForm').attr('action', `/aplikasi/${app.id_aplikasi}`);
                    
                    // Populate form fields
                    $('#edit_nama').val(app.nama);
                    $('#edit_opd').val(app.opd);
                    $('#edit_uraian').val(app.uraian);
                    $('#edit_tahun_pembuatan').val(app.tahun_pembuatan);
                    $('#edit_jenis').val(app.jenis);
                    $('#edit_basis_aplikasi').val(app.basis_aplikasi);
                    $('#edit_bahasa_framework').val(app.bahasa_framework);
                    $('#edit_database').val(app.database);
                    $('#edit_pengembang').val(app.pengembang);
                    $('#edit_lokasi_server').val(app.lokasi_server);
                    $('#edit_status_pemakaian').val(app.status_pemakaian);

                    // Show modal
                    $('#editModal').modal('show');
                } else {
                    toastr.error('Gagal memuat data aplikasi');
                }
            },
            error: function(xhr) {
                console.error('Ajax error:', xhr);
                toastr.error('Terjadi kesalahan saat memuat data');
            }
        });
    }

    // Form submit handler
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        
        // Tambahkan token CSRF
        const data = new FormData(form[0]);
        data.append('_method', 'PUT'); // Untuk method spoofing
        
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    toastr.success('Aplikasi berhasil diperbarui');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Gagal memperbarui aplikasi');
                }
            },
            error: function(xhr) {
                console.error('Ajax error:', xhr);
                toastr.error('Terjadi kesalahan saat memperbarui data');
            }
        });
    });

    function deleteApp(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data aplikasi akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/aplikasi/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                'Data aplikasi berhasil dihapus.',
                                'success'
                            );
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    // Add this code after your existing scripts
    document.addEventListener('DOMContentLoaded', function() {
        const toggleViewBtn = document.getElementById('toggleView');
        const cardView = document.getElementById('cardView');
        const tableView = document.getElementById('tableView');
        const toggleIcon = toggleViewBtn.querySelector('i');
        const toggleText = toggleViewBtn.querySelector('span');

        toggleViewBtn.addEventListener('click', function() {
            if (cardView.style.display !== 'none') {
                // Switch to table view
                cardView.style.display = 'none';
                tableView.style.display = 'block';
                toggleIcon.className = 'bi bi-grid';
                toggleText.textContent = 'Tampilan Card';
            } else {
                // Switch to card view
                cardView.style.display = 'flex';
                tableView.style.display = 'none';
                toggleIcon.className = 'bi bi-table';
                toggleText.textContent = 'Tampilan Tabel';
            }
        });
    });
    </script>
</body>
</html>