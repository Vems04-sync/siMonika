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

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

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
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm ms-1" 
                                                    onclick="return confirm('Yakin ingin menghapus atribut ini?')">
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
                    <form action="{{ route('atribut.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Aplikasi</label>
                                <select name="id_aplikasi" class="form-select" required>
                                    <option value="">Pilih Aplikasi</option>
                                    @foreach($aplikasis as $aplikasi)
                                    <option value="{{ $aplikasi->id_aplikasi }}">{{ $aplikasi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Atribut</label>
                                <input type="text" name="nama_atribut" class="form-control" required>
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
                    <form id="editAtributForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Aplikasi</label>
                                <select name="id_aplikasi" class="form-select" required>
                                    @foreach($aplikasis as $aplikasi)
                                    <option value="{{ $aplikasi->id_aplikasi }}">{{ $aplikasi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Atribut</label>
                                <input type="text" name="nama_atribut" class="form-control" required>
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
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/atribut/index.js') }}"></script>
</body>
</html> 