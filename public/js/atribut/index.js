$(document).ready(function() {
    // Inisialisasi Select2 pada modal tambah
    $('#tambahAtributModal .select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('#tambahAtributModal'),
        placeholder: 'Pilih Aplikasi',
        allowClear: true,
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });

    // Inisialisasi Select2 pada modal edit
    $('#editAtributModal .select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('#editAtributModal'),
        placeholder: 'Pilih Aplikasi',
        allowClear: true,
        language: {
            noResults: function() {
                return "Data tidak ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });

    // Reset Select2 saat modal ditutup
    $('#tambahAtributModal').on('hidden.bs.modal', function () {
        $('.select2').val('').trigger('change');
    });

    // Handle tombol edit
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        
        // Ambil data atribut melalui AJAX
        $.ajax({
            url: `/atribut/${id}/edit`,
            method: 'GET',
            success: function(response) {
                // Isi form edit dengan data yang diterima
                $('#editAtributForm').attr('action', `/atribut/${id}`);
                $('#editAtributModal select[name="id_aplikasi"]').val(response.id_aplikasi).trigger('change');
                $('#editAtributModal input[name="nama_atribut"]').val(response.nama_atribut);
                $('#editAtributModal input[name="nilai_atribut"]').val(response.nilai_atribut);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat mengambil data');
            }
        });
    });
}); 