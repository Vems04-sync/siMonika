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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Daftar Aplikasi</h2>
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
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>OPD</th>
                        <th>Status</th>
                        <th>Tahun Pembuatan</th>
                        <th>Jenis</th>
                        <th>Basis Aplikasi</th>
                        <th>Bahasa/Framework</th>
                        <th>Database</th>
                        <th>Pengembang</th>
                        <th>Lokasi Server</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aplikasis as $aplikasi)
                        <tr>
                            <td>{{ $aplikasi->nama }}</td>
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
                            <td>{{ $aplikasi->basis_aplikasi }}</td>
                            <td>{{ $aplikasi->bahasa_framework }}</td>
                            <td>{{ $aplikasi->database }}</td>
                            <td>{{ $aplikasi->pengembang }}</td>
                            <td>{{ $aplikasi->lokasi_server }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="viewAppDetails('{{ $aplikasi->nama }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="editApp('{{ $aplikasi->nama }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteApp('{{ $aplikasi->nama }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <!-- Data akan diisi secara dinamis -->
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

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebar.js"></script>
    <script>
        // Toggle View Handler
        let isCardView = true;
        const toggleViewBtn = document.getElementById('toggleView');
        const appGrid = document.getElementById('appGrid');
        const appTable = document.getElementById('appTable');

        toggleViewBtn.addEventListener('click', function() {
            isCardView = !isCardView;

            if (isCardView) {
                appGrid.style.display = 'flex';
                appTable.style.display = 'none';
                toggleViewBtn.innerHTML = '<i class="bi bi-grid"></i><span class="ms-2">Ubah Tampilan</span>';
            } else {
                appGrid.style.display = 'none';
                appTable.style.display = 'block';
                toggleViewBtn.innerHTML = '<i class="bi bi-card-list"></i><span class="ms-2">Ubah Tampilan</span>';
                setupPagination();
                showTablePage();
            }
        });

        // Pagination Configuration
        const itemsPerPage = 10;
        let currentPage = 1;

        function setupPagination() {
            const table = document.querySelector('#appTable tbody');
            const rows = table.querySelectorAll('tr');
            const pageCount = Math.ceil(rows.length / itemsPerPage);

            // Create pagination container if not exists
            let paginationContainer = document.querySelector('#tablePagination');
            if (!paginationContainer) {
                paginationContainer = document.createElement('div');
                paginationContainer.id = 'tablePagination';
                paginationContainer.className = 'd-flex justify-content-end mt-3';
                document.querySelector('#appTable').appendChild(paginationContainer);
            }

            // Generate pagination HTML
            let paginationHtml = '<ul class="pagination">';
            // Previous button
            paginationHtml += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                </li>
            `;

            // Page numbers
            for (let i = 1; i <= pageCount; i++) {
                paginationHtml += `
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Next button
            paginationHtml += `
                <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>
            `;
            paginationHtml += '</ul>';

            paginationContainer.innerHTML = paginationHtml;

            // Add click events to pagination
            const paginationLinks = paginationContainer.querySelectorAll('.page-link');
            paginationLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newPage = parseInt(link.dataset.page);
                    if (newPage >= 1 && newPage <= pageCount) {
                        currentPage = newPage;
                        showTablePage();
                        setupPagination();
                    }
                });
            });
        }

        function showTablePage() {
            const table = document.querySelector('#appTable tbody');
            const rows = table.querySelectorAll('tr');

            // Hide all rows
            rows.forEach(row => row.style.display = 'none');

            // Calculate start and end index for current page
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            // Show rows for current page
            for (let i = start; i < end && i < rows.length; i++) {
                rows[i].style.display = '';
            }
        }

        // Modify existing filterApps function
        function filterApps() {
            const searchTerm = document.getElementById('searchApp').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            // Filter cards
            const cards = document.querySelectorAll('.app-card');
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const status = card.dataset.status;
                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                card.style.display = matchesSearch && matchesStatus ? 'block' : 'none';
            });

            // Filter table rows
            const rows = document.querySelectorAll('#appTable tbody tr');
            let visibleRows = [];

            rows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const status = row.cells[2].textContent.trim().toLowerCase() === 'aktif' ? 'active' : 'unused';
                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;

                if (matchesSearch && matchesStatus) {
                    visibleRows.push(row);
                }
                row.style.display = 'none';
            });

            // Reset pagination for filtered results
            currentPage = 1;

            // Show first page of filtered results
            const start = 0;
            const end = Math.min(itemsPerPage, visibleRows.length);
            for (let i = start; i < end; i++) {
                visibleRows[i].style.display = '';
            }

            // Update pagination
            if (searchTerm || statusFilter) {
                const paginationContainer = document.querySelector('#tablePagination');
                if (paginationContainer) {
                    paginationContainer.style.display = 'none';
                }
            } else {
                setupPagination();
                const paginationContainer = document.querySelector('#tablePagination');
                if (paginationContainer) {
                    paginationContainer.style.display = '';
                }
            }
        }

        // Event Listeners
        document.getElementById('searchApp').addEventListener('input', filterApps);
        document.getElementById('statusFilter').addEventListener('change', filterApps);

        // View Details
        function viewAppDetails(appId) {
            fetch(`/aplikasi/detail/${appId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Aplikasi tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Bersihkan tbody sebelum menambahkan data baru
                    const tbody = document.querySelector('#detailModal .table tbody');
                    tbody.innerHTML = '';

                    // Mapping nama kolom ke label yang lebih user-friendly
                    const columnLabels = {
                        'id': 'ID',
                        'nama': 'Nama Aplikasi',
                        'opd': 'OPD',
                        'uraian': 'Uraian',
                        'tahun_pembuatan': 'Tahun Pembuatan',
                        'jenis': 'Jenis',
                        'basis_aplikasi': 'Basis Aplikasi',
                        'bahasa_framework': 'Bahasa/Framework',
                        'database': 'Database',
                        'pengembang': 'Pengembang',
                        'lokasi_server': 'Lokasi Server',
                        'status_pemakaian': 'Status Pemakaian',
                        'created_at': 'Dibuat Pada',
                        'updated_at': 'Diperbarui Pada'
                    };

                    // Tambahkan baris untuk setiap kolom
                    for (const [key, value] of Object.entries(data)) {
                        // Skip kolom yang tidak perlu ditampilkan
                        if (['id', 'created_at', 'updated_at'].includes(key)) continue;

                        const row = document.createElement('tr');
                        const label = columnLabels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l
                    .toUpperCase());

                        row.innerHTML = `
                            <th width="30%">${label}</th>
                            <td>${value || '-'}</td>
                        `;
                        tbody.appendChild(row);
                    }

                    // Tampilkan modal
                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil detail aplikasi');
                });
        }

        // Add Application
        function addApp(appId) {
            document.getElementById('modalTitle').textContent = 'Tambah Aplikasi';
            const modal = new bootstrap.Modal(document.getElementById('appModal'));
            modal.show();
        }

        // Edit Application
        function editApp(appId) {
            // Fetch data aplikasi berdasarkan nama
            fetch(`/aplikasi/edit/${appId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Aplikasi tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Edit Aplikasi';

                    // Isi form dengan data yang ada
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('opd').value = data.opd;
                    document.getElementById('uraian').value = data.uraian;
                    document.getElementById('tahun_pembuatan').value = data.tahun_pembuatan;
                    document.getElementById('jenis').value = data.jenis;
                    document.getElementById('basis_aplikasi').value = data.basis_aplikasi;
                    document.getElementById('bahasa_framework').value = data.bahasa_framework;
                    document.getElementById('database').value = data.database;
                    document.getElementById('pengembang').value = data.pengembang;
                    document.getElementById('lokasi_server').value = data.lokasi_server;
                    document.getElementById('status_pemakaian').value = data.status_pemakaian;

                    // Set form action untuk update
                    const form = document.getElementById('appForm');
                    form.action = `/aplikasi/${appId}`;
                    form.method = 'POST';

                    // Tambahkan method field untuk PUT request
                    let methodField = document.getElementById('_method');
                    if (!methodField) {
                        methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.id = '_method';
                        form.appendChild(methodField);
                    }
                    methodField.value = 'PUT';

                    // Tampilkan modal
                    const modal = new bootstrap.Modal(document.getElementById('appModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data aplikasi');
                });
        }

        // Delete Application
        function deleteApp(appId) {
            if (confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')) {
                // Buat form untuk delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/aplikasi/delete/${appId}`;

                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                // Tambahkan method _method untuk DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                // Gabungkan semua element
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);

                // Submit form
                form.submit();
            }
        }

        // Save Application
        function saveApp() {
            // In a real application, this would save the app data
            alert('Aplikasi disimpan!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('appModal'));
            modal.hide();
        }

        // Initialize pagination when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (!isCardView) {
                setupPagination();
                showTablePage();
            }
        });
    </script>
</body>

</html>
