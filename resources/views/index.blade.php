<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiMonika</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
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
                                <h2 class="card-title mb-0">{{ $jumlahAplikasiAktif }}</h2> 
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
                                <h2 class="card-title mb-0">{{ $jumlahAplikasiTidakDigunakan }}</h2> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row - Gunakan library Chart.js atau yang serupa -->
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header border-0 bg-white">
                        <h5 class="card-title">Status Aplikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header border-0 bg-white">
                        <h5 class="card-title">Pengguna Aktif per Aplikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="userBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Status Aplikasi</h5>
                </div>
            </div>
            <!-- Table -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Aplikasi</th>
                                <th>OPD</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($aplikasis) && $aplikasis->isNotEmpty())
                                @foreach($aplikasis as $aplikasi)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="app-icon me-3 bg-primary bg-opacity-10 p-2 rounded"> 
                                                    <i class="bi bi-app text-primary"></i> 
                                                </div>
                                                <span>{{ $aplikasi->nama }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $aplikasi->opd }}</td>
                                        <td>
                                            @if ($aplikasi->status_pemakaian == 'Aktif')
                                                <span class="status-badge status-active">Aktif</span>
                                            @else
                                                <span class="status-badge status-unused">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data aplikasi tersedia.</td>
                                </tr>
                            @endif
                        </tbody>                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="{{ asset('js/index/chart.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script> 
</body>

</html>
