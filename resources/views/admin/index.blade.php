@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Kelola Admin</h2>
                <p class="text-muted">Manajemen data admin sistem</p>
            </div>
            <div class="button-action">
                <button class="btn btn-primary" onclick="addAdmin()">
                    <i class="bi bi-plus-lg"></i>
                    <span class="me-2">Tambah Admin</span>
                </button>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Admin Table Card -->
        <div class="table-responsive">
            <div class="shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $index => $admin)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $admin->nama }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                            {{ $admin->email }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $admin->role === 'super_admin' ? 'bg-danger' : 'bg-primary' }}">
                                            {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group" role="group">
                                            <a href="javascript:void(0)" onclick="editAdmin('{{ $admin->id_user }}')"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.destroy', $admin->id_user) }}" method="POST"
                                                class="d-inline delete-form"
                                                onsubmit="return handleDelete(event, this);">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mt-2">Belum ada data admin</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Admin -->
    <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="adminForm" method="POST" action="{{ route('admin.store') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Nama Admin -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Admin</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" required placeholder="Masukkan nama admin">
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" required placeholder="Masukkan email admin">
                            </div>
                            <div class="invalid-feedback"></div>
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

    <style>
        .fade-out {
            animation: fadeOut 0.5s ease forwards;
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(-20px);
            }
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function addAdmin() {
            // Reset form
            document.getElementById('adminForm').reset();
            document.getElementById('modalTitle').textContent = 'Tambah Admin';

            // Reset action ke route store
            document.getElementById('adminForm').action = "{{ route('admin.store') }}";

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('adminModal'));
            modal.show();
        }

        function editAdmin(id) {
            // Set judul modal
            document.getElementById('modalTitle').textContent = 'Edit Admin';

            // Ambil data admin
            fetch(`/admin/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Isi form dengan data yang ada
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('email').value = data.email;

                    // Update form action untuk update
                    const form = document.getElementById('adminForm');
                    form.action = `/admin/${id}`;

                    // Tambahkan method PUT
                    if (!form.querySelector('input[name="_method"]')) {
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'PUT';
                        form.appendChild(methodField);
                    }

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('adminModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data admin');
                });
        }

        // Handle form submission with AJAX
        document.getElementById('adminForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide modal
                        bootstrap.Modal.getInstance(document.getElementById('adminModal')).hide();

                        // Show success message
                        alert(data.message);

                        if (data.requireRelogin) {
                            if (data.isCurrentUser) {
                                // Jika yang diubah adalah user yang sedang login
                                window.location.href = '/login';
                            } else {
                                // Jika yang diubah adalah user lain
                                window.location.reload();
                            }
                        } else {
                            window.location.reload();
                        }
                    } else {
                        // Show validation errors
                        Object.keys(data.errors).forEach(key => {
                            const input = document.querySelector(`[name="${key}"]`);
                            input.classList.add('is-invalid');
                            const feedback = input.closest('.mb-3').querySelector('.invalid-feedback');
                            feedback.textContent = data.errors[key][0];
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        });

        function handleDelete(e, form) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data admin akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const row = form.closest('tr');
                    row.classList.add('fade-out');
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => {
                                window.location.reload(); // Refresh halaman setelah delete berhasil
                            }, 500);
                        } else {
                            row.classList.remove('fade-out');
                        }
                    })
                    .catch(() => {
                        row.classList.remove('fade-out');
                    });
                }
            });
            
            return false;
        }
    </script>
@endsection
