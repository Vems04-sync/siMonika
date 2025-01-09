<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiMonika</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <h2 class="mb-0">Dashboard Monitoring</h2>
            <div class="d-flex align-items-center">
                <i class="bi bi-clock me-2"></i>
                <span>Update Terakhir: Hari ini 14:30 WIB</span>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card stat-card h-100">
                    <div class="card-body status-active">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2">Aplikasi Aktif</h6>
                                <h2 class="card-title-info mb-0">{{ $jumlahAplikasiAktif }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100">
                    <div class="card-body status-unused">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2">Aplikasi Tidak Digunakan</h6>
                                <h2 class="card-title-info mb-0">{{ $jumlahAplikasiTidakDigunakan }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambahkan include template charts -->
        @include('templates.charts')

        <div class="card">
            <div class="card-header border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Status Aplikasi</h5>
                </div>
                <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center mt-3">
                    <label for="per_page" class="me-2">Tampilkan:</label>
                    <select id="per_page" class="form-select form-select-sm w-auto">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </form>
            </div>
            <!-- Table -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="data-table">
                        <thead>
                            <tr>
                                <th>Nama Aplikasi</th>
                                <th>Status</th>
                                <th>Jenis Aplikasi</th>
                                <th>Basis Aplikasi</th>
                                <th>Pengembang</th>
                            </tr>
                        </thead>
                        <tbody id="data-body">
                            @foreach ($aplikasis as $aplikasi)
                                <tr>
                                    <td>{{ $aplikasi->nama }}</td>
                                    <td>
                                        @if ($aplikasi->status_pemakaian == 'Aktif')
                                            <span class="status-badge status-active">Aktif</span>
                                        @else
                                            <span class="status-badge status-unused">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $aplikasi->jenis }}</td>
                                    <td>{{ $aplikasi->basis_aplikasi }}</td>
                                    <td>{{ $aplikasi->pengembang }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div id="pagination-controls" class="d-flex justify-content-center">
                        {{ $aplikasis->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/index/chart.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <!-- JavaScript Tampilan Data Tabel -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const allData = @json($allData); // Data lengkap dikirim dari controller
            const perPageSelect = document.getElementById('per_page');
            const dataBody = document.getElementById('data-body');
            const paginationControls = document.getElementById('pagination-controls');

            let perPage = 10;
            let currentPage = 1;

            // Fungsi untuk merender tabel berdasarkan halaman dan jumlah per halaman
            function renderTable() {
                const start = (currentPage - 1) * perPage;
                const end = start + perPage;
                const pageData = allData.slice(start, end);

                // Bersihkan isi tabel
                dataBody.innerHTML = '';
                pageData.forEach(item => {
                    const row = `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="app-icon me-3 bg-primary bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-app text-primary"></i>
                                    </div>
                                    ${item.nama}
                                </div>
                            </td>
                            <td>${item.status_pemakaian === 'Aktif' ? 
                                '<span class="status-badge status-active">Aktif</span>' :
                                '<span class="status-badge status-unused">Tidak Aktif</span>'}</td>
                            <td>${item.jenis}</td>
                            <td>${item.basis_aplikasi}</td>
                            <td>${item.pengembang}</td>
                        </tr>
                    `;
                    dataBody.innerHTML += row;
                });

                renderPagination();
            }

            // Fungsi untuk merender kontrol paginasi
            function renderPagination() {
                const totalPages = Math.ceil(allData.length / perPage);
                let controlsHTML = '';

                // Tombol "Previous"
                controlsHTML +=
                    `<button class="btn btn-primary me-2" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">Previous</button>`;

                // Tombol halaman
                for (let i = 1; i <= totalPages; i++) {
                    controlsHTML +=
                        `<button class="btn ${i === currentPage ? 'btn-secondary' : 'btn-outline-secondary'} me-1" onclick="changePage(${i})">${i}</button>`;
                }

                // Tombol "Next"
                controlsHTML +=
                    `<button class="btn btn-primary ms-2" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">Next</button>`;

                paginationControls.innerHTML = controlsHTML;
            }

            // Fungsi untuk mengubah halaman
            window.changePage = (page) => {
                currentPage = page;
                renderTable();
            };

            // Event listener untuk dropdown perPage
            perPageSelect.addEventListener('change', (e) => {
                perPage = parseInt(e.target.value);
                currentPage = 1; // Reset ke halaman pertama
                renderTable();
            });

            // Render awal
            renderTable();
        });
    </script>
</body>

</html>
