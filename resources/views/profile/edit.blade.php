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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
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
            <h2 class="mb-0">Edit Profile</h2>
        </div>

        <!-- Settings Sections -->
        <div class="row">
            <div class="col-md-3">
                <!-- Settings Navigation -->
                <div class="card mb-4">
                    <div class="list-group list-group-flush">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                            <i class="bi bi-person me-2"></i>Profil Pengguna
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="bi bi-shield-lock me-2"></i>Keamanan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Profile Settings -->
                    <div class="tab-pane fade show active" id="profile">
                        <div class="card">
                            <div class="card-header bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Profil Pengguna</h5>
                                    <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-left me-1"></i>Kembali
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text"
                                                class="form-control @error('nama') is-invalid @enderror" name="nama"
                                                value="{{ old('nama', auth()->user()->nama) }}">
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control"
                                                value="{{ auth()->user()->email }}" disabled>
                                            <small class="text-muted">Email tidak dapat diubah</small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                                        </button>
                                        <a href="{{ route('profile.index') }}" class="btn btn-light">
                                            <i class="bi bi-x-lg me-1"></i>Batal
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="tab-pane fade" id="security">
                        <div class="card">
                            <div class="card-header bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Keamanan</h5>
                                    <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-left me-1"></i>Kembali
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.updatePassword') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <h6>Ubah Password</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" id="password">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePassword">
                                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                                        </button>
                                        <a href="{{ route('profile.index') }}" class="btn btn-light">
                                            <i class="bi bi-x-lg me-1"></i>Batal
                                        </a>
                                    </div>
                                </form>
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
    <script>
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>

</html>
