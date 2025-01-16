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

    <form action="{{ route('aplikasi.update', $aplikasi->nama) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Aplikasi:</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $aplikasi->nama }}" required>
        </div>

        <div class="mb-3">
            <label for="opd" class="form-label">OPD:</label>
            <input type="text" class="form-control" id="opd" name="opd" value="{{ $aplikasi->opd }}" required>
        </div>

        <div class="mb-3">
            <label for="uraian" class="form-label">Uraian:</label>
            <textarea class="form-control" id="uraian" name="uraian">{{ $aplikasi->uraian }}</textarea>
        </div>

        <div class="mb-3">
            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan:</label>
            <input type="date" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" value="{{ $aplikasi->tahun_pembuatan }}">
        </div>

        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis:</label>
            <select class="form-control" id="jenis" name="jenis" required>
                <option value="Layanan Publik" {{ $aplikasi->jenis == 'Layanan Publik' ? 'selected' : '' }}>Layanan Publik</option>
                <option value="Administrasi Pemerintahan" {{ $aplikasi->jenis == 'Administrasi Pemerintahan' ? 'selected' : '' }}>Administrasi Pemerintahan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="basis_aplikasi" class="form-label">Basis Aplikasi:</label>
            <select class="form-control" id="basis_aplikasi" name="basis_aplikasi" required>
                <option value="Mobile" {{ $aplikasi->basis_aplikasi == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="Web" {{ $aplikasi->basis_aplikasi == 'Web' ? 'selected' : '' }}>Web</option>
                <option value="Desktop" {{ $aplikasi->basis_aplikasi == 'Desktop' ? 'selected' : '' }}>Desktop</option>
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

        <div class="row g-3 mt-3">
            <div class="col-12">
                <h5>Atribut Tambahan</h5>
                @foreach($atributs as $atribut)
                    @if(isset($existingAtributs[$atribut->id_atribut]))
                        <div class="mb-3">
                            <label for="atribut_{{ $atribut->id_atribut }}" class="form-label">
                                {{ $atribut->nama_atribut }}
                                <small class="text-muted">({{ ucfirst($atribut->tipe_data) }})</small>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="atribut_{{ $atribut->id_atribut }}"
                                   name="atribut[{{ $atribut->id_atribut }}]"
                                   value="{{ $existingAtributs[$atribut->id_atribut] }}">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('aplikasi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</body>
</html> 