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
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-download"></i> Export Log
                    </button>
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
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->user->nama }}</td>
                            <td>
                                <span class="badge bg-{{ $log->tipe_aktivitas === 'create' ? 'success' : ($log->tipe_aktivitas === 'update' ? 'warning' : 'danger') }}">
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
</div>
@endsection
