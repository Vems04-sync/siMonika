// Konfigurasi global toastr
toastr.options = {
    "closeButton": true,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "timeOut": "3000"
};

// Fungsi untuk menampilkan flash message
function showFlashMessages() {
    const flashMessage = localStorage.getItem('flash_message');
    if (flashMessage) {
        toastr.success(flashMessage, "Berhasil");
        localStorage.removeItem('flash_message');
    }
}

// Panggil fungsi saat dokumen siap
$(document).ready(function() {
    showFlashMessages();
    
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

    // Handle form submit untuk delete
    $('form[method="POST"]').on('submit', function(e) {
        if ($(this).find('input[name="_method"]').val() === 'DELETE') {
            e.preventDefault();
            if (confirm('Yakin ingin menghapus atribut ini?')) {
                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Langsung redirect tanpa menyimpan ke localStorage
                        window.location.href = '/atribut';
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan saat menghapus data', 'Error');
                    }
                });
            }
        }
    });

    // Handle form submit untuk tambah dan edit
    $('#tambahAtributModal form, #editAtributModal form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const isEdit = form.find('input[name="_method"]').val() === 'PUT';

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                localStorage.setItem('flash_message', 
                    isEdit ? 'Data atribut berhasil diperbarui!' : 'Data atribut berhasil ditambahkan!'
                );
                window.location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '<ul class="m-0">';
                    Object.values(errors).forEach(error => {
                        errorMessage += `<li>${error[0]}</li>`;
                    });
                    errorMessage += '</ul>';
                    toastr.error(errorMessage, 'Validasi Gagal');
                } else {
                    toastr.error('Terjadi kesalahan saat menyimpan data', 'Error');
                }
            }
        });
    });

    // Handle tombol edit
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `/atribut/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#editAtributForm').attr('action', `/atribut/${id}`);
                $('#editAtributModal select[name="id_aplikasi"]').val(response.id_aplikasi).trigger('change');
                $('#editAtributModal input[name="nama_atribut"]').val(response.nama_atribut);
                $('#editAtributModal input[name="nilai_atribut"]').val(response.nilai_atribut);
            },
            error: function(xhr) {
                toastr.error('Gagal mengambil data atribut', 'Error');
            }
        });
    });
}); 