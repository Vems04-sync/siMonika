@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Dashboard Super Admin</h2>
                <p class="text-muted">Monitoring aktivitas sistem</p>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Admin</h5>
                        <h2 class="mb-0">{{ $total_admin }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Aplikasi</h5>
                        <h2 class="mb-0">{{ $total_aplikasi }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Aplikasi Aktif</h5>
                        <h2 class="mb-0">{{ $aplikasi_aktif }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Aplikasi Tidak Aktif</h5>
                        <h2 class="mb-0">{{ $aplikasi_tidak_aktif }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Aktivitas -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Log Aktivitas Admin</h5>
                    <div>
                        <form action="{{ route('super-admin.log.export') }}" method="GET" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download"></i> Export Log
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Admin</th>
                                <th>Aktivitas</th>
                                <th>Modul</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($log_aktivitas as $log)
                                <tr>
                                    <td>{{ $log->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                                    <td>{{ $log->user->nama }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $log->tipe_aktivitas === 'create' ? 'success' : ($log->tipe_aktivitas === 'update' ? 'warning' : 'danger') }}">
                                            {{ $log->aktivitas }}
                                        </span>
                                    </td>
                                    <td>{{ $log->modul }}</td>
                                    <td>{{ $log->detail }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada aktivitas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $log_aktivitas->links() }}
            </div>
        </div>

        <!-- Admin Aktif -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Admin Aktif</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Admin</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aktivitas Terakhir</th>
                                <th>Aksi Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admin_aktif as $admin)
                                <tr>
                                    <td>{{ $admin['nama'] }}</td>
                                    <td>{{ $admin['email'] }}</td>
                                    <td>
                                        @if ($admin['status'] === 'Online')
                                            <span class="badge bg-success">Online</span>
                                        @else
                                            <span class="badge bg-secondary">Offline</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($admin['last_activity'])
                                            {{ \Carbon\Carbon::parse($admin['last_activity'])->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $admin['last_action'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada admin yang aktif</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
