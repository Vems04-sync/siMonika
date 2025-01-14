<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atribut - SiMonika</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Tambahkan CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
        }
        .select2-container--bootstrap-5 .select2-selection--single {
            padding: 0.375rem 0.75rem;
        }
        .is-invalid + .select2-container--bootstrap-5 .select2-selection,
        .is-invalid.select2-selection {
            border-color: #dc3545;
        }
        .is-invalid + .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .is-invalid + .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
    </style>
    <!-- Tambahkan Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Di bagian head, tambahkan CSS SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-sm">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" style="width: 50px">No</th>
                                <th scope="col" style="width: 25%">Nama Aplikasi</th>
                                <th scope="col" style="width: 25%">Nama Atribut</th>
                                <th scope="col">Nilai Atribut</th>
                                <th scope="col" style="width: 180px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($atributs as $index => $atribut)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="app-icon me-2 bg-primary bg-opacity-10 p-1 rounded">
                                            <i class="bi bi-app text-primary"></i>
                                        </div>
                                        <span class="small">{{ $atribut->aplikasi->nama }}</span>
                                    </div>
                                </td>
                                <td class="small">{{ $atribut->nama_atribut }}</td>
                                <td class="small">{{ $atribut->nilai_atribut ?? '-' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-warning btn-sm edit-btn" 
                                                data-id="{{ $atribut->id_atribut }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editAtributModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('atribut.destroy', $atribut->id_atribut) }}" 
                                              method="POST" 
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm ms-1 delete-btn">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    <i class="bi bi-inbox text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                                    <span class="text-muted small">Belum ada data atribut</span>
                                </td>
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
                            Tambah Atribut
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('atribut.store') }}" method="POST" id="tambahAtributForm" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Aplikasi <span class="text-danger">*</span></label>
                                <select name="id_aplikasi" class="form-select select2" required>
                                    <option value="">Pilih Aplikasi</option>
                                    @foreach($aplikasis as $aplikasi)
                                    <option value="{{ $aplikasi->id_aplikasi }}">{{ $aplikasi->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Silakan pilih aplikasi
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Atribut <span class="text-danger">*</span></label>
                                <input type="text" name="nama_atribut" class="form-control" required>
                                <div class="invalid-feedback">
                                    Nama atribut tidak boleh kosong
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nilai Atribut</label>
                                <input type="text" name="nilai_atribut" class="form-control">
                                <div class="form-text">Opsional</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan
                            </button>
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
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Atribut
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editAtributForm" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Aplikasi <span class="text-danger">*</span></label>
                                <select name="id_aplikasi" class="form-select select2" required>
                                    <option value="">Pilih Aplikasi</option>
                                    @foreach($aplikasis as $aplikasi)
                                    <option value="{{ $aplikasi->id_aplikasi }}">{{ $aplikasi->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Silakan pilih aplikasi
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Atribut <span class="text-danger">*</span></label>
                                <input type="text" name="nama_atribut" class="form-control" required>
                                <div class="invalid-feedback">
                                    Nama atribut tidak boleh kosong
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nilai Atribut</label>
                                <input type="text" name="nilai_atribut" class="form-control">
                                <div class="form-text">Opsional</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update
                            </button>
                        </div>
                    </form>
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
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.success("{{ session('success') }}", "Berhasil");
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.error("{{ session('error') }}", "Error");
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}", "Error");
            @endforeach
        });
    </script>
    @endif

    <script>
        // Konfigurasi default untuk toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // Cek dan tampilkan flash message dari localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessage = localStorage.getItem('flash_message');
            if (flashMessage) {
                toastr.success(flashMessage);
                localStorage.removeItem('flash_message');
            }
        });
    </script>

    <!-- Di bagian bawah sebelum </body>, tambahkan JS SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html> 