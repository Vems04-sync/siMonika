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
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Yakin ingin menghapus atribut ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            localStorage.setItem('flash_message', response.message);
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Terjadi kesalahan saat menghapus data');
                        }
                    }
                });
            }
        });
    });

    // Handle form submit untuk tambah atribut
    $('#tambahAtributModal form').on('submit', function(e) {
        e.preventDefault();
        
        // Ambil nilai input
        const aplikasi = $('select[name="id_aplikasi"]').val();
        const namaAtribut = $('input[name="nama_atribut"]').val().trim();
        
        // Validasi input
        if (!aplikasi) {
            toastr.error('Silakan pilih aplikasi terlebih dahulu');
            return false;
        }
        
        if (!namaAtribut) {
            toastr.error('Nama atribut tidak boleh kosong');
            return false;
        }
        
        // Lanjutkan dengan AJAX request jika validasi berhasil
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    localStorage.setItem('flash_message', response.message);
                    window.location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Terjadi kesalahan saat memproses permintaan');
                }
            }
        });
    });

    // Reset form dan pesan error saat modal ditutup
    $('#tambahAtributModal').on('hidden.bs.modal', function () {
        $('#tambahAtributForm')[0].reset();
        $('.select2').val('').trigger('change');
    });

    // Validasi input saat mengetik
    $('input[name="nama_atribut"]').on('input', function() {
        const value = $(this).val().trim();
        if (value === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Validasi select2 saat berubah
    $('select[name="id_aplikasi"]').on('change', function() {
        const value = $(this).val();
        if (!value) {
            $(this).next('.select2-container').find('.select2-selection').addClass('is-invalid');
        } else {
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });

    // Kode untuk edit atribut
    $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        $.get('/atribut/' + id + '/edit', function(data) {
            $('#editAtributModal select[name="id_aplikasi"]').val(data.id_aplikasi).trigger('change');
            $('#editAtributModal input[name="nama_atribut"]').val(data.nama_atribut);
            $('#editAtributModal input[name="nilai_atribut"]').val(data.nilai_atribut);
            $('#editAtributForm').attr('action', '/atribut/' + id);
        });
    });

    // Update handler untuk form edit
    $('#editAtributModal form').on('submit', function(e) {
        e.preventDefault();
        
        // Ambil nilai input
        const aplikasi = $('#editAtributModal select[name="id_aplikasi"]').val();
        const namaAtribut = $('#editAtributModal input[name="nama_atribut"]').val().trim();
        
        // Validasi input
        if (!aplikasi) {
            toastr.error('Silakan pilih aplikasi terlebih dahulu');
            return false;
        }
        
        if (!namaAtribut) {
            toastr.error('Nama atribut tidak boleh kosong');
            return false;
        }
        
        // Lanjutkan dengan AJAX request jika validasi berhasil
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    localStorage.setItem('flash_message', response.message);
                    window.location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Terjadi kesalahan saat memproses permintaan');
                }
            }
        });
    });

    // Reset form edit dan pesan error saat modal ditutup
    $('#editAtributModal').on('hidden.bs.modal', function () {
        $('#editAtributForm')[0].reset();
        $('#editAtributModal .select2').val('').trigger('change');
        $('#editAtributModal .is-invalid').removeClass('is-invalid');
    });

    // Validasi input untuk form edit
    $('#editAtributModal input[name="nama_atribut"]').on('input', function() {
        const value = $(this).val().trim();
        if (value === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Validasi select2 untuk form edit
    $('#editAtributModal select[name="id_aplikasi"]').on('change', function() {
        const value = $(this).val();
        if (!value) {
            $(this).next('.select2-container').find('.select2-selection').addClass('is-invalid');
        } else {
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
}); 