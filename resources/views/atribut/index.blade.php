<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atribut - siMonika</title>
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Atribut</li>
                        </ol>
                    </nav>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Kelola Atribut</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAtributModal">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Atribut
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Aplikasi</th>
                                            <th>Nama Atribut</th>
                                            <th>Nilai Atribut</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($atributs as $index => $atribut)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $atribut->aplikasi->nama }}</td>
                                            <td>{{ $atribut->nama_atribut }}</td>
                                            <td>{{ $atribut->nilai_atribut ?? '-' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-btn" 
                                                        data-id="{{ $atribut->id_atribut }}"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editAtributModal">
                                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                                </button>
                                                <form action="{{ route('atribut.destroy', $atribut->id_atribut) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Yakin ingin menghapus?')">
                                                        <i class="bi bi-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data atribut</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Atribut -->
        <div class="modal fade" id="tambahAtributModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Atribut</h5>
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
                                <small class="text-muted">Opsional</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                        <h5 class="modal-title">Edit Atribut</h5>
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
                                <small class="text-muted">Opsional</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Edit Button Click
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/atribut/${id}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        const form = document.getElementById('editAtributForm');
                        form.action = `/atribut/${id}`;
                        form.querySelector('[name="id_aplikasi"]').value = data.id_aplikasi;
                        form.querySelector('[name="nama_atribut"]').value = data.nama_atribut;
                        form.querySelector('[name="nilai_atribut"]').value = data.nilai_atribut || '';
                    });
            });
        });
    });
    </script>
</body>
</html> 