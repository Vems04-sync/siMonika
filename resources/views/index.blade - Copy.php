<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiMonika</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
</head>
<body>
    <!-- Navbar dengan tombol logout -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">SiMonika</a>
            <div class="d-flex">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <h2>Daftar Aplikasi</h2>
            </div>
            <div class="col text-end">
                <a href="{{ route('aplikasi.create') }}" class="btn btn-primary">Tambah Aplikasi</a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>OPD</th>
                        <th>Uraian</th>
                        <th>Tahun Pembuatan</th>
                        <th>Jenis</th>
                        <th>Basis Aplikasi</th>
                        <th>Bahasa Framework</th>
                        <th>Database</th>
                        <th>Pengembang</th>
                        <th>Lokasi Server</th>
                        <th>Status Pemakaian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aplikasis as $index => $aplikasi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $aplikasi->nama }}</td>
                        <td>{{ $aplikasi->opd }}</td>
                        <td>{{ $aplikasi->uraian }}</td>
                        <td>{{ $aplikasi->tahun_pembuatan }}</td>
                        <td>{{ $aplikasi->jenis }}</td>
                        <td>{{ $aplikasi->basis_aplikasi }}</td>
                        <td>{{ $aplikasi->bahasa_framework }}</td>
                        <td>{{ $aplikasi->database }}</td>
                        <td>{{ $aplikasi->pengembang }}</td>
                        <td>{{ $aplikasi->lokasi_server }}</td>
                        <td>{{ $aplikasi->status_pemakaian }}</td>
                        <td>
                            <a href="{{ route('aplikasi.edit', $aplikasi->id_aplikasi) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('aplikasi.destroy', $aplikasi->id_aplikasi) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>