<!DOCTYPE html>
<html>
<head>
    <title>Daftar Aplikasi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Daftar Aplikasi</h1>
    
    <a href="{{ route('aplikasi.create') }}">Tambah Aplikasi Baru</a>
    
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
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
            @foreach ($aplikasis as $aplikasi)
                <tr>
                    <td>{{ $aplikasi->id_aplikasi }}</td>
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
                        <a href="{{ route('aplikasi.edit', $aplikasi->id_aplikasi) }}">Edit</a>
                        <form action="{{ route('aplikasi.destroy', $aplikasi->id_aplikasi) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>