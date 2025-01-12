<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile - siMonika</title>
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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Profile</h2>
                <p class="text-muted">Pengaturan informasi akun pengguna</p>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-circle" style="font-size: 5rem; color: #2c3e50;"></i>
                        </div>
                        <h5 class="card-title mb-0">{{ auth()->user()->nama }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">Quick Links</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house-door me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('aplikasi.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-grid me-2"></i>Aplikasi
                        </a>
                        <a href="{{ route('atribut.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-clipboard me-2"></i>Atribut
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Profile Details -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Informasi Profile</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 text-muted">Nama Lengkap</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="mb-0">{{ auth()->user()->nama }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 text-muted">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="mb-0">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 text-muted">Role</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="mb-0">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">Aktivitas Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <i class="bi bi-person text-primary"></i>
                                <p class="mb-2">Profile terakhir diperbarui</p>
                                <small class="text-muted">{{ auth()->user()->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html> 