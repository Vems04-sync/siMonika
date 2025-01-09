<!DOCTYPE html>
<html>
<head>
    <title>Detail Aplikasi</title>
</head>
<body>
    <h1>Detail Aplikasi: {{ $aplikasi->nama }}</h1>
    <p>OPD: {{ $aplikasi->opd }}</p>
    <p>Status Pemakaian: {{ $aplikasi->status_pemakaian }}</p>
    <p>Tahun Pembuatan: {{ $aplikasi->tahun_pembuatan }}</p>
    <p>Jenis: {{ $aplikasi->jenis }}</p>
    <p>Basis Aplikasi: {{ $aplikasi->basis_aplikasi }}</p>
    <p>Bahasa/Framework: {{ $aplikasi->bahasa_framework }}</p>
    <p>Database: {{ $aplikasi->database }}</p>
    <p>Pengembang: {{ $aplikasi->pengembang }}</p>
    <p>Lokasi Server: {{ $aplikasi->lokasi_server }}</p>
    <p>Uraian: {{ $aplikasi->uraian }}</p>
    <a href="{{ route('aplikasi.index') }}">Kembali</a>
</body>
</html>
