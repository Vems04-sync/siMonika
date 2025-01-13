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
</head>

<body>
    <!-- Sidebar -->
    @include('templates/sidebar')

    <!-- Main Content -->
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                    <span class="me-2">Ubah Tampilan</span>
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
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari aplikasi..." id="searchApp">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4" id="appGrid">
            @foreach ($aplikasis as $aplikasi)
                <div class="col-md-6 col-lg-4 app-card"
                    data-status="{{ $aplikasi->status_pemakaian == 'Aktif' ? 'active' : 'unused' }}">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="app-icon me-3 bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="bi bi-app text-primary"></i>
                                </div>
                                <h5 class="card-title mb-0">{{ $aplikasi->nama }}</h5>
                            </div>
                            <div class="mb-3">
                                @if ($aplikasi->status_pemakaian == 'Aktif')
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-unused">Tidak Aktif</span>
                                @endif
                            </div>
                            <div class="app-details">
                                <p class="text-muted mb-2">
                                    <i class="bi bi-calendar"></i>
                                    Tahun Pembuatan: {{ $aplikasi->tahun_pembuatan }}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-back"></i>
                                    Jenis: {{ $aplikasi->jenis }}
                                </p>
                                <p class="text-muted mb-2">
                                    @if ($aplikasi->basis_aplikasi === 'Desktop')
                                        <i class="bi bi-laptop me-2"></i>
                                    @elseif ($aplikasi->basis_aplikasi === 'Mobile')
                                        <i class="bi bi-phone me-2"></i>
                                    @elseif ($aplikasi->basis_aplikasi === 'Website')
                                        <i class="bi bi-browser-chrome me-2"></i>
                                    @else
                                        <i class="bi bi-people me-2"></i>
                                    @endif
                                    Basis Aplikasi: {{ $aplikasi->basis_aplikasi }}
                                </p>

                                <p class="text-muted mb-2">
                                    <i class="bi bi-gear"></i>
                                    Bahasa Framework: {{ $aplikasi->bahasa_framework }}
                                </p>
                            </div>
                            <div class="app-notes mt-3">
                                <small class="text-muted">
                                    {{ $aplikasi->uraian }}
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-primary btn-sm"
                                    onclick="viewAppDetails('{{ $aplikasi->nama }}')">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>
                                <div>
                                    <button class="btn btn-outline-secondary btn-sm me-2"
                                        onclick="editApp('{{ $aplikasi->nama }}')">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm"
                                        onclick="deleteApp('{{ $aplikasi->nama }}')">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View (hidden by default) -->
        <div class="table-responsive" id="appTable" style="display: none;">
            <div class="shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4">Nama</th>
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
                            @foreach ($aplikasis as $aplikasi)
                                <tr>
                                    <td class="fw-medium ps-4">{{ $aplikasi->nama }}</td>
                                    <td>{{ $aplikasi->opd }}</td>
                                    <td>
                                        @if ($aplikasi->status_pemakaian == 'Aktif')
                                            <span class="status-badge status-active">Aktif</span>
                                        @else
                                            <span class="status-badge status-unused">Tidak Aktif</span>
                                        @endif
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
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="viewAppDetails('{{ $aplikasi->nama }}')"
                                                title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="editApp('{{ $aplikasi->nama }}')" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="deleteApp('{{ $aplikasi->nama }}')" title="Hapus">
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

    <!-- Modal -->
    <div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah/Edit Aplikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="appForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Aplikasi</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="opd" class="form-label">OPD</label>
                            <input type="text" class="form-control" id="opd" name="opd" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraian" class="form-label">Uraian</label>
                            <textarea class="form-control" id="uraian" name="uraian"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                            <input type="date" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan">
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <input type="text" class="form-control" id="jenis" name="jenis" required>
                        </div>
                        <div class="mb-3">
                            <label for="basis_aplikasi" class="form-label">Basis Aplikasi</label>
                            <select class="form-select" id="basis_aplikasi" name="basis_aplikasi" required>
                                <option value="Website">Website</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Mobile">Mobile</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bahasa_framework" class="form-label">Bahasa/Framework</label>
                            <input type="text" class="form-control" id="bahasa_framework" name="bahasa_framework"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="database" class="form-label">Database</label>
                            <input type="text" class="form-control" id="database" name="database" required>
                        </div>
                        <div class="mb-3">
                            <label for="pengembang" class="form-label">Pengembang</label>
                            <input type="text" class="form-control" id="pengembang" name="pengembang" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi_server" class="form-label">Lokasi Server</label>
                            <input type="text" class="form-control" id="lokasi_server" name="lokasi_server"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="status_pemakaian" class="form-label">Status Pemakaian</label>
                            <select class="form-select" id="status_pemakaian" name="status_pemakaian" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
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

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalTitle">Detail Aplikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add loading state -->
                    <div id="loadingState" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Add error state -->
                    <div id="errorState" class="alert alert-danger d-none">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="errorMessage">Terjadi kesalahan saat mengambil data</span>
                    </div>

                    <!-- Content state -->
                    <div id="contentState">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody id="detailTable">
                                    <!-- Data aplikasi akan diisi secara dinamis -->
                                </tbody>
                            </table>

                            <h6 class="mt-4 mb-3">Atribut Tambahan</h6>
                            <div id="atributContent">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Atribut</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="atributTable">
                                        <!-- Atribut akan diisi secara dinamis -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="noAtributMessage" class="text-muted text-center p-3 d-none">
                                <i class="bi bi-info-circle me-2"></i>Tidak ada atribut tambahan
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

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/aplikasi/index.js') }}"></script>
</body>

</html>
