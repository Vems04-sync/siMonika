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

        <div class="card">
            <div class="card-body">
                <!-- Tambahkan form pencarian dan filter di sini -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select id="atributFilter" class="form-select">
                            <option value="">Semua Atribut</option>
                            @php
                                $uniqueAtributs = $atributs->pluck('nama_atribut')->unique();
                            @endphp
                            @foreach ($uniqueAtributs as $nama_atribut)
                                <option value="{{ $nama_atribut }}">{{ $nama_atribut }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Atribut</th>
                                <th>Jumlah Aplikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($atributs as $index => $atribut)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $atribut->nama_atribut }}</td>
                                    <td>{{ $atribut->aplikasis->count() }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detailAtributModal"
                                            data-atribut-id="{{ $atribut->id_atribut }}">
                                            Detail
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="deleteAtribut({{ $atribut->id_atribut }})">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada atribut</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Setelah card tabel atribut yang sudah ada -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="mb-3">Daftar Aplikasi</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                                        <button class="btn btn-sm btn-info"
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
                                <select name="tipe_data" class="form-select" required>
                                    <option value="">Pilih Tipe Data</option>
                                    <option value="varchar">Text (VARCHAR)</option>
                                    <option value="number">Angka (NUMBER)</option>
                                    <option value="date">Tanggal (DATE)</option>
                                    <option value="text">Text Panjang (TEXT)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nilai Default (Opsional)</label>
                                <input type="text" name="nilai_default" class="form-control">
                                <div class="form-text">Nilai awal yang akan diterapkan ke semua aplikasi</div>
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
                        <h5 class="modal-title">Detail Atribut: <span id="atributName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Menampilkan daftar aplikasi yang menggunakan atribut ini
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Aplikasi</th>
                                        <th>Nilai Atribut</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="detailAtributContent">
                                    <!-- Diisi dengan JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btnAddToApps">
                            <i class="bi bi-plus-circle me-1"></i>Tambah ke Aplikasi Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Nilai Atribut -->
        <div class="modal fade" id="editNilaiModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Nilai Atribut</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" id="nilai_atribut">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary btn-simpan-nilai">Simpan</button>
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
        $(document).ready(function() {
            let currentAtributId;

            // Handler untuk tombol detail
            $('[data-bs-target="#detailAtributModal"]').on('click', function() {
                const atributId = $(this).data('atribut-id');
                currentAtributId = atributId;
                loadAtributDetail(atributId);
            });

            function loadAtributDetail(atributId) {
                $.ajax({
                    url: `/atribut/${atributId}/detail`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#atributName').text(response.atribut.nama_atribut);
                            const tbody = $('#detailAtributContent');
                            tbody.empty();

                            if (response.aplikasis.length === 0) {
                                tbody.append(`
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada aplikasi yang menggunakan atribut ini</td>
                                </tr>
                            `);
                            } else {
                                response.aplikasis.forEach((item, index) => {
                                    tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.nama}</td>
                                        <td>${item.pivot.nilai_atribut || '-'}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-warning btn-edit-nilai" 
                                                        data-aplikasi-id="${item.id_aplikasi}"
                                                        data-nilai="${item.pivot.nilai_atribut || ''}"
                                                        title="Edit Nilai">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger btn-remove-atribut" 
                                                        data-aplikasi-id="${item.id_aplikasi}"
                                                        title="Hapus dari Aplikasi">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                                });
                            }
                            // Setup handlers setelah konten dimuat
                            setupActionHandlers();
                        } else {
                            toastr.error('Gagal memuat detail atribut');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan saat memuat detail atribut');
                    }
                });
            }

            function updateNilaiAtribut(aplikasiId, nilaiAtribut) {
                // Tambahkan log untuk debugging
                console.log('Updating nilai atribut:', {
                    aplikasiId,
                    nilaiAtribut,
                    currentAtributId
                });

                $.ajax({
                    url: `/atribut/${aplikasiId}/nilai`,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id_atribut: currentAtributId,
                        nilai: nilaiAtribut
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Nilai atribut berhasil diupdate');
                            loadAtributDetail(currentAtributId);
                        } else {
                            toastr.error(response.message || 'Gagal mengupdate nilai atribut');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating:', xhr);
                        toastr.error('Terjadi kesalahan saat mengupdate nilai atribut');
                    }
                });
            }

            function setupActionHandlers() {
                // Handler untuk edit nilai
                $(document).on('click', '.btn-edit-nilai', function() {
                    const aplikasiId = $(this).data('aplikasi-id');
                    const currentNilai = $(this).data('nilai');

                    // Tampilkan modal edit yang sudah ada di HTML
                    $('#editNilaiModal').modal('show');

                    // Set nilai ke input
                    $('#nilai_atribut').val(currentNilai);

                    // Handler untuk tombol Simpan
                    $('.btn-simpan-nilai').off('click').on('click', function() {
                        const nilai = $('#nilai_atribut').val();
                        if (!nilai) {
                            toastr.error('Nilai tidak boleh kosong!');
                            return;
                        }

                        $.ajax({
                            url: `/atribut/${aplikasiId}/nilai`,
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id_atribut: currentAtributId,
                                nilai: nilai
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#editNilaiModal').modal('hide');
                                    toastr.success('Nilai atribut berhasil diupdate');
                                    loadAtributDetail(currentAtributId);
                                } else {
                                    toastr.error(response.message ||
                                        'Gagal mengupdate nilai atribut');
                                }
                            },
                            error: function(xhr) {
                                console.error('Error updating:', xhr);
                                toastr.error(
                                    'Terjadi kesalahan saat mengupdate nilai atribut'
                                );
                            }
                        });
                    });
                });

                // Handler untuk hapus atribut dari aplikasi
                $('.btn-remove-atribut').on('click', function() {
                    const aplikasiId = $(this).data('aplikasi-id');

                    Swal.fire({
                        title: 'Hapus Atribut?',
                        text: 'Atribut akan dihapus dari aplikasi ini',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeAtributFromApp(aplikasiId);
                        }
                    });
                });
            }

            function removeAtributFromApp(aplikasiId) {
                $.ajax({
                    url: `/atribut/${aplikasiId}/${currentAtributId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Atribut berhasil dihapus dari aplikasi');
                            loadAtributDetail(currentAtributId);
                        } else {
                            toastr.error('Gagal menghapus atribut dari aplikasi');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan saat menghapus atribut');
                    }
                });
            }
        });
    </script>

    <script>
        function saveNilaiAtribut(aplikasiId) {
            const nilai = $('#nilai_atribut').val();

            if (!nilai) {
                toastr.error('Nilai tidak boleh kosong!');
                return;
            }

            // Tambahkan log untuk debugging
            console.log('Saving nilai:', {
                aplikasiId: aplikasiId,
                atributId: currentAtributId,
                nilai: nilai
            });

            updateNilaiAtribut(aplikasiId, nilai);
        }
    </script>

    <script>
        $(document).ready(function() {
            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function saveNilaiAtribut(aplikasiId) {
                const nilai = $('#nilai_atribut').val();

                if (!nilai) {
                    toastr.error('Nilai tidak boleh kosong!');
                    return;
                }

                // Tambahkan log untuk debugging
                console.log('Saving nilai:', {
                    aplikasiId: aplikasiId,
                    atributId: currentAtributId,
                    nilai: nilai
                });

                updateNilaiAtribut(aplikasiId, nilai);
            }

            function updateNilaiAtribut(aplikasiId, nilaiAtribut) {
                $.ajax({
                    url: `/atribut/${aplikasiId}/nilai`,
                    method: 'PUT',
                    data: {
                        id_atribut: currentAtributId,
                        nilai: nilaiAtribut
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#editNilaiModal').modal('hide');
                            toastr.success('Nilai atribut berhasil diupdate');
                            loadAtributDetail(currentAtributId);
                        } else {
                            toastr.error(response.message || 'Gagal mengupdate nilai atribut');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating:', xhr.responseJSON);
                        toastr.error(xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat mengupdate nilai atribut');
                    }
                });
            }
        });
    </script>
</body>

</html>
