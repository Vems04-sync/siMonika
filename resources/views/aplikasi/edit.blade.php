<!DOCTYPE html>
<html>
<head>
    <title>Edit Aplikasi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Edit Aplikasi</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('aplikasi.update', $aplikasi->id_aplikasi) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="nama">Nama Aplikasi:</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $aplikasi->nama) }}" required>
        </div>

        <div>
            <label for="opd">OPD:</label><br>
            <input type="text" id="opd" name="opd" value="{{ old('opd', $aplikasi->opd) }}" required>
        </div>

        <div>
            <label for="uraian">Uraian:</label><br>
            <textarea id="uraian" name="uraian">{{ old('uraian', $aplikasi->uraian) }}</textarea>
        </div>

        <div>
            <label for="tahun_pembuatan">Tahun Pembuatan:</label><br>
            <input type="date" id="tahun_pembuatan" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $aplikasi->tahun_pembuatan) }}">
        </div>

        <div>
            <label for="jenis">Jenis:</label><br>
            <select id="jenis" name="jenis">
                <option value="Layanan Publik" {{ old('jenis', $aplikasi->jenis) == 'Layanan Publik' ? 'selected' : '' }}>Layanan Publik</option>
                <option value="Administrasi Pemerintahan" {{ old('jenis', $aplikasi->jenis) == 'Administrasi Pemerintahan' ? 'selected' : '' }}>Administrasi Pemerintahan</option>
                <option value="Fungsi Tertentu" {{ old('jenis', $aplikasi->jenis) == 'Fungsi Tertentu' ? 'selected' : '' }}>Fungsi Tertentu</option>
            </select>
        </div>

        <div>
            <label for="basis_aplikasi">Basis Aplikasi:</label><br>
            <select id="basis_aplikasi" name="basis_aplikasi">
                <option value="Mobile" {{ old('basis_aplikasi', $aplikasi->basis_aplikasi) == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="Website" {{ old('basis_aplikasi', $aplikasi->basis_aplikasi) == 'Website' ? 'selected' : '' }}>Website</option>
                <option value="Desktop" {{ old('basis_aplikasi', $aplikasi->basis_aplikasi) == 'Desktop' ? 'selected' : '' }}>Desktop</option>
            </select>
        </div>

        <div>
            <label for="bahasa_framework">Bahasa Pemrograman/Framework:</label><br>
            <input type="text" id="bahasa_framework" name="bahasa_framework" value="{{ old('bahasa_framework', $aplikasi->bahasa_framework) }}">
        </div>

        <div>
            <label for="database">Database:</label><br>
            <select id="database" name="database">
                <option value="MySQL" {{ old('database', $aplikasi->database) == 'MySQL' ? 'selected' : '' }}>MySQL</option>
                <option value="PostgreSQL" {{ old('database', $aplikasi->database) == 'PostgreSQL' ? 'selected' : '' }}>PostgreSQL</option>
                <option value="MongoDB" {{ old('database', $aplikasi->database) == 'MongoDB' ? 'selected' : '' }}>MongoDB</option>
            </select>
        </div>

        <div>
            <label for="pengembang">Pengembang:</label><br>
            <select id="pengembang" name="pengembang">
                <option value="Internal OPD" {{ old('pengembang', $aplikasi->pengembang) == 'Internal OPD' ? 'selected' : '' }}>Internal OPD</option>
                <option value="Diskominfo" {{ old('pengembang', $aplikasi->pengembang) == 'Diskominfo' ? 'selected' : '' }}>Diskominfo</option>
                <option value="Vendor" {{ old('pengembang', $aplikasi->pengembang) == 'Vendor' ? 'selected' : '' }}>Vendor</option>
            </select>
        </div>

        <div>
            <label for="lokasi_server">Lokasi Server:</label><br>
            <select id="lokasi_server" name="lokasi_server">
                <option value="Server Diskominfo" {{ old('lokasi_server', $aplikasi->lokasi_server) == 'Server Diskominfo' ? 'selected' : '' }}>Server Diskominfo</option>
                <option value="Server Internal OPD" {{ old('lokasi_server', $aplikasi->lokasi_server) == 'Server Internal OPD' ? 'selected' : '' }}>Server Internal OPD</option>
                <option value="PDN" {{ old('lokasi_server', $aplikasi->lokasi_server) == 'PDN' ? 'selected' : '' }}>PDN</option>
                <option value="Vendor" {{ old('lokasi_server', $aplikasi->lokasi_server) == 'Vendor' ? 'selected' : '' }}>Vendor</option>
            </select>
        </div>

        <div>
            <label for="status_pemakaian">Status Pemakaian:</label><br>
            <select id="status_pemakaian" name="status_pemakaian">
                <option value="Aktif" {{ old('status_pemakaian', $aplikasi->status_pemakaian) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ old('status_pemakaian', $aplikasi->status_pemakaian) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('aplikasi.index') }}">Kembali</a>
        </div>
    </form>
</body>
</html> 