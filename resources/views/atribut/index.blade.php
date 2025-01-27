<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atribut - SiMonika</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Tambahkan CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            padding: 0.375rem 0.75rem;
        }

        .select2-container--bootstrap-5 .select2-search__field {
            padding: 0.375rem 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .select2-container--bootstrap-5 .select2-search__field:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #0d6efd;
            color: white;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 0.375rem 0.75rem;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding: 0;
            line-height: 1.5;
        }
    </style>
    <!-- Tambahkan Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Di bagian head, tambahkan: -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Sidebar -->
    @include('templates.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Kelola Atribut</h2>
                <p class="text-muted">Manajemen atribut tambahan aplikasi</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAtributModal">
                <i class="bi bi-plus-lg me-1"></i>Tambah Atribut
            </button>
        </div>

        <!-- Filter untuk tabel atribut -->
        <div class="row mb-3">
            <div class="col-md-4">
                <select id="atributFilter" class="form-select">
                    <option value="">Semua Atribut</option>
                    @foreach ($atributs as $atribut)
                        <option value="{{ strtolower($atribut->nama_atribut) }}">
                            {{ $atribut->nama_atribut }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tabel atribut dengan id spesifik -->
        <div class="table-responsive">
            <table class="table table-hover" id="tabelAtribut">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Atribut</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($atributs as $index => $atribut)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $atribut->nama_atribut }}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#detailAtributModal"
                                    onclick="loadAtributDetail('{{ $atribut->id_atribut }}')">
                                    <i class="bi bi-info-circle"></i> Detail
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="deleteAtribut('{{ $atribut->id_atribut }}')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data atribut</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Search untuk tabel aplikasi -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Aplikasi</h5>
                    <div class="col-md-4">
                        <input type="text" id="searchAplikasi" class="form-control" placeholder="Cari aplikasi...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabelAplikasi">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Aplikasi</th>
                                <th>OPD</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aplikasis as $index => $aplikasi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $aplikasi->nama }}</td>
                                    <td>{{ $aplikasi->opd }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $aplikasi->status_pemakaian === 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $aplikasi->status_pemakaian }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-secondary"
                                            onclick="showAppDetail({{ $aplikasi->id_aplikasi }})">
                                            <i class="bi bi-info-circle"></i> Detail
                                        </button>
                                        <button class="btn btn-sm btn-warning"
                                            onclick="editAppAtribut({{ $aplikasi->id_aplikasi }})">
                                            <i class="bi bi-pencil"></i> Edit Atribut
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada aplikasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Atribut -->
        <div class="modal fade" id="tambahAtributModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Atribut Global
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('atribut.store') }}" method="POST" id="formTambahAtribut">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Atribut</label>
                                <input type="text" name="nama_atribut" class="form-control" required>
                                <div class="form-text">Nama atribut yang akan ditambahkan ke semua aplikasi</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe Data</label>
                                <select name="tipe_data" class="form-select" required id="tipeDataSelect">
                                    <option value="">Pilih Tipe Data</option>
                                    <option value="varchar">Text (VARCHAR)</option>
                                    <option value="number">Angka (NUMBER)</option>
                                    <option value="date">Tanggal (DATE)</option>
                                    <option value="text">Text Panjang (TEXT)</option>
                                    <option value="enum">Enum (ENUM)</option>
                                </select>
                            </div>
                            <div id="enumOptionsContainer" style="display: none;">
                                <label class="form-label">Opsi Enum</label>
                                <div id="enumOptions"></div>
                                <button type="button" class="btn btn-secondary" id="addEnumOption">Tambah Opsi</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Atribut -->
        <div class="modal fade" id="editAtributModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Atribut Aplikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editAtributForm">
                            <div id="atributFields"></div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Atribut -->
        <div class="modal fade" id="detailAtributModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Atribut</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Aplikasi</th>
                                        <th>Nilai Atribut</th>
                                    </tr>
                                </thead>
                                <tbody id="detailAtributContent">
                                    <!-- Content will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Aplikasi -->
        <div class="modal fade" id="detailAppModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Aplikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%">Nama Aplikasi</td>
                                        <td width="60%" id="detail-nama"></td>
                                    </tr>
                                    <tr>
                                        <td>OPD</td>
                                        <td id="detail-opd"></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td id="detail-status"></td>
                                    </tr>
                                    <tr>
                                        <td>Pengembang</td>
                                        <td id="detail-pengembang"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Atribut Tambahan:</h6>
                                <div id="detail-atribut"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Tambahkan Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/atribut/index.js') }}"></script>

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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", "Error");
                @endforeach
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            function validateAttribute(input, appSelect, currentId = '') {
                const nama_atribut = input.val();
                const id_aplikasi = appSelect.val();

                $.ajax({
                    url: input.data('validation-url'),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama_atribut: nama_atribut,
                        id_aplikasi: id_aplikasi,
                        current_id: currentId
                    },
                    success: function(response) {
                        if (response.exists) {
                            input.addClass('is-invalid');
                            input.closest('form').find('button[type="submit"]').prop('disabled', true);
                        } else {
                            input.removeClass('is-invalid');
                            input.closest('form').find('button[type="submit"]').prop('disabled', false);
                        }
                    }
                });
            }

            // Validate on input change for create form
            $('#tambahAtributModal input[name="nama_atribut"]').on('input', function() {
                validateAttribute(
                    $(this),
                    $('#tambahAtributModal select[name="id_aplikasi"]')
                );
            });

            $('#tambahAtributModal select[name="id_aplikasi"]').on('change', function() {
                validateAttribute(
                    $('#tambahAtributModal input[name="nama_atribut"]'),
                    $(this)
                );
            });

            // Validate on input change for edit form
            $('#editAtributModal input[name="nama_atribut"]').on('input', function() {
                validateAttribute(
                    $(this),
                    $('#editAtributModal select[name="id_aplikasi"]'),
                    $(this).data('current-id')
                );
            });

            $('#editAtributModal select[name="id_aplikasi"]').on('change', function() {
                validateAttribute(
                    $('#editAtributModal input[name="nama_atribut"]'),
                    $(this),
                    $('#editAtributModal input[name="nama_atribut"]').data('current-id')
                );
            });
        });
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deleteAtribut(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data atribut akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form untuk delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('atribut') }}/${id}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        // Script untuk mengisi form edit
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            const form = $('#editAtributForm');

            // Set action URL dengan ID yang benar
            form.attr('action', `{{ url('atribut') }}/${id}`);

            // Ambil data dari server
            $.get(`{{ url('atribut') }}/${id}/edit`, function(data) {
                $('#edit_aplikasi_nama').val(data.aplikasi.nama);
                $('#edit_id_aplikasi').val(data.id_aplikasi);
                $('#edit_nama_atribut').val(data.nama_atribut);
                $('#edit_nilai_atribut').val(data.nilai_atribut);
            });
        });
    </script>

    <script>
        let currentAtributId = null;
        let currentAplikasiId = null;

        function loadAtributDetail(atributId) {
            currentAtributId = atributId;

            $.ajax({
                url: `/atribut/${atributId}/detail`,
                method: 'GET',
                success: function(response) {
                    let html = '';
                    response.aplikasis.forEach((aplikasi, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${aplikasi.nama}</td>
                                <td>${aplikasi.pivot.nilai_atribut || '-'}</td>
                            </tr>
                        `;
                    });
                    $('#detailAtributContent').html(html);
                },
                error: function() {
                    toastr.error('Gagal memuat detail atribut');
                }
            });
        }
    </script>

    <script>
        function showAppDetail(id) {
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

                        // Update informasi dasar aplikasi
                        $('#detail-nama').text(app.nama || '-');
                        $('#detail-opd').text(app.opd || '-');
                        $('#detail-status').text(app.status_pemakaian || '-');
                        $('#detail-pengembang').text(app.pengembang || '-');

                        // Update atribut tambahan dengan format yang lebih baik
                        let atributHtml = '<div class="table-responsive"><table class="table table-bordered">';
                        atributHtml += '<thead><tr><th>Nama Atribut</th><th>Nilai</th></tr></thead><tbody>';

                        if (app.atribut_tambahans && app.atribut_tambahans.length > 0) {
                            app.atribut_tambahans.forEach(atribut => {
                                const nilai = atribut.pivot.nilai_atribut || '-';
                                atributHtml += `
                                    <tr>
                                        <td><strong>${atribut.nama_atribut}</strong></td>
                                        <td>${nilai}</td>
                                    </tr>`;
                            });
                        } else {
                            atributHtml +=
                                '<tr><td colspan="2" class="text-center">Tidak ada atribut tambahan</td></tr>';
                        }

                        atributHtml += '</tbody></table></div>';
                        $('#detail-atribut').html(atributHtml);

                        // Tampilkan modal
                        $('#detailAppModal').modal('show');
                    } else {
                        toastr.error(response.message || 'Gagal memuat detail aplikasi');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', {
                        xhr,
                        status,
                        error
                    }); // Debug log
                    toastr.error('Terjadi kesalahan saat memuat data');
                }
            });
        }
    </script>
</body>

</html>
