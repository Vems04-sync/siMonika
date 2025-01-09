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
</head>

<body>
    <!-- Sidebar -->
    @include('templates/sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Daftar Aplikasi</h2>
            <div class="button-action">
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

        @include('aplikasi/create')
    </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebar.js"></script>
    <script>
        // Search and Filter
        function filterApps() {
            const searchTerm = document.getElementById('searchApp').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.app-card');

            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const status = card.dataset.status;
                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;

                card.style.display = matchesSearch && matchesStatus ? 'block' : 'none';
            });
        }

        // Event Listeners
        document.getElementById('searchApp').addEventListener('input', filterApps);
        document.getElementById('statusFilter').addEventListener('change', filterApps);

        // View Details
        function viewAppDetails(appId) {
            // In a real application, this would navigate to a details page
            alert('Melihat detail aplikasi: ' + appId);
        }

        // Add Application
        function addApp(appId) {
            document.getElementById('modalTitle').textContent = 'Tambah Aplikasi';
            const modal = new bootstrap.Modal(document.getElementById('appModal'));
            modal.show();
        }

        // Edit Application
        function editApp(appId) {
            document.getElementById('modalTitle').textContent = 'Edit Aplikasi';
            const modal = new bootstrap.Modal(document.getElementById('appModal'));
            modal.show();
        }

        // Delete Application
        function deleteApp(appId) {
            if (confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')) {
                // In a real application, this would delete the app
                alert('Aplikasi dihapus: ' + appId);
            }
        }

        // Save Application
        function saveApp() {
            // In a real application, this would save the app data
            alert('Aplikasi disimpan!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('appModal'));
            modal.hide();
        }
    </script>
</body>

</html>
