// Toggle View Handlerf


let isCardView = true;
const toggleViewBtn = document.getElementById('toggleView');
const appGrid = document.getElementById('appGrid');
const appTable = document.getElementById('appTable');

toggleViewBtn.addEventListener('click', function() {
    isCardView = !isCardView;

    if (isCardView) {
        appGrid.style.display = 'flex';
        appTable.style.display = 'none';
        toggleViewBtn.innerHTML = '<i class="bi bi-grid"></i><span class="ms-2">Ubah Tampilan</span>';
    } else {
        appGrid.style.display = 'none';
        appTable.style.display = 'block';
        toggleViewBtn.innerHTML = '<i class="bi bi-card-list"></i><span class="ms-2">Ubah Tampilan</span>';
        setupPagination();
        showTablePage();
    }
});

// Pagination Configuration
const itemsPerPage = 10;
let currentPage = 1;

function setupPagination() {
    const table = document.querySelector('#appTable tbody');
    const visibleRows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
    const pageCount = Math.ceil(visibleRows.length / itemsPerPage);

    let paginationContainer = document.querySelector('#tablePagination');
    if (!paginationContainer) {
        paginationContainer = document.createElement('div');
        paginationContainer.id = 'tablePagination';
        paginationContainer.className = 'd-flex justify-content-end mt-3';
        document.querySelector('#appTable').appendChild(paginationContainer);
    }

    // Pastikan currentPage tidak melebihi pageCount
    if (currentPage > pageCount) {
        currentPage = pageCount || 1;
    }

    let paginationHtml = '<ul class="pagination">';
    paginationHtml += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
        </li>
    `;

    for (let i = 1; i <= pageCount; i++) {
        paginationHtml += `
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }

    paginationHtml += `
        <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
        </li>
    </ul>`;

    paginationContainer.innerHTML = paginationHtml;

    // Update event listeners untuk pagination
    const paginationLinks = paginationContainer.querySelectorAll('.page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const newPage = parseInt(link.dataset.page);
            if (newPage >= 1 && newPage <= pageCount) {
                currentPage = newPage;
                showTablePage();
                setupPagination();
            }
        });
    });
}

function showTablePage() {
    const table = document.querySelector('#appTable tbody');
    const visibleRows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
    
    // Sembunyikan semua baris terlebih dahulu
    visibleRows.forEach(row => row.style.display = 'none');
    
    // Tampilkan hanya baris yang sesuai dengan halaman saat ini
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    
    for (let i = start; i < end && i < visibleRows.length; i++) {
        visibleRows[i].style.display = '';
    }
}

function filterApps() {
    const searchTerm = document.getElementById('searchApp').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const jenisFilter = document.getElementById('jenisFilter').value;
    const basisFilter = document.getElementById('basisFilter').value;
    const bahasaFilter = document.getElementById('bahasaFilter').value;
    const databaseFilter = document.getElementById('databaseFilter').value;
    const pengembangFilter = document.getElementById('pengembangFilter').value;
    const lokasiFilter = document.getElementById('lokasiFilter').value;

    // Filter cards
    const cards = document.querySelectorAll('.app-card');
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const status = card.dataset.status;
        const jenis = card.dataset.jenis;
        const basis = card.dataset.basis;
        const bahasa = card.dataset.bahasa;
        const database = card.dataset.database;
        const pengembang = card.dataset.pengembang;
        const lokasi = card.dataset.lokasi;
        
        const matchesSearch = !searchTerm || title.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesJenis = !jenisFilter || jenis.toLowerCase() === jenisFilter.toLowerCase();
        const matchesBasis = !basisFilter || basis.toLowerCase() === basisFilter.toLowerCase();
        const matchesBahasa = !bahasaFilter || bahasa.toLowerCase() === bahasaFilter.toLowerCase();
        const matchesDatabase = !databaseFilter || database.toLowerCase() === databaseFilter.toLowerCase();
        const matchesPengembang = !pengembangFilter || pengembang.toLowerCase() === pengembangFilter.toLowerCase();
        const matchesLokasi = !lokasiFilter || lokasi.toLowerCase() === lokasiFilter.toLowerCase();
        
        if (matchesSearch && matchesStatus && matchesJenis && matchesBasis && 
            matchesBahasa && matchesDatabase && matchesPengembang && matchesLokasi) {
            card.closest('.col-md-6').style.display = '';
        } else {
            card.closest('.col-md-6').style.display = 'none';
        }
    });

    // Filter table rows
    const rows = document.querySelectorAll('#appTable tbody tr');
    rows.forEach(row => {
        // Mengambil nilai dari sel tabel dengan lebih tepat
        const appName = row.cells[0].textContent.toLowerCase();
        const status = row.querySelector('.status-badge').textContent.trim().toLowerCase() === 'aktif' ? 'active' : 'unused';
        
        // Perbaikan cara mengambil nilai dari sel tabel
        const jenis = row.cells[4].textContent.trim(); // Kolom Jenis
        const basis = row.cells[5].querySelector('i')?.nextSibling?.textContent.trim() || row.cells[5].textContent.trim(); // Kolom Basis Aplikasi
        const bahasa = row.cells[6].textContent.trim(); // Kolom Bahasa/Framework
        const database = row.cells[7].textContent.trim(); // Kolom Database
        const pengembang = row.cells[8].textContent.trim(); // Kolom Pengembang
        const lokasi = row.cells[9].textContent.trim(); // Kolom Lokasi Server
        
        const matchesSearch = !searchTerm || appName.includes(searchTerm);
        const matchesStatus = !statusFilter || (
            (statusFilter === 'active' && status === 'active') || 
            (statusFilter === 'unused' && status === 'unused')
        );
        
        // Perbandingan yang lebih tepat untuk setiap filter
        const matchesJenis = !jenisFilter || jenis.toLowerCase().includes(jenisFilter.toLowerCase());
        const matchesBasis = !basisFilter || basis.toLowerCase().includes(basisFilter.toLowerCase());
        const matchesBahasa = !bahasaFilter || bahasa.toLowerCase().includes(bahasaFilter.toLowerCase());
        const matchesDatabase = !databaseFilter || database.toLowerCase().includes(databaseFilter.toLowerCase());
        const matchesPengembang = !pengembangFilter || pengembang.toLowerCase().includes(pengembangFilter.toLowerCase());
        const matchesLokasi = !lokasiFilter || lokasi.toLowerCase().includes(lokasiFilter.toLowerCase());

        const shouldShow = matchesSearch && matchesStatus && matchesJenis && 
            matchesBasis && matchesBahasa && matchesDatabase && 
            matchesPengembang && matchesLokasi;
            
        row.style.display = shouldShow ? '' : 'none';
    });

    // Reset dan update pagination setelah filtering
    if (!isCardView) {
        currentPage = 1; // Reset ke halaman pertama
        setupPagination();
        showTablePage();
    }
}

// Event listeners untuk semua filter
document.addEventListener('DOMContentLoaded', function() {
    const filters = [
        'statusFilter', 'jenisFilter', 'basisFilter',
        'bahasaFilter', 'databaseFilter', 'pengembangFilter', 'lokasiFilter'
    ];
    
    // Tambahkan event listener untuk setiap filter
    filters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', filterApps);
        }
    });

    // Event listener untuk search dengan debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchApp');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterApps, 300);
        });
    }

    // Inisialisasi filter saat halaman dimuat
    filterApps();
});

// CRUD Operations
function viewAppDetails(nama) {
    document.getElementById('loadingState').classList.remove('d-none');
    document.getElementById('errorState').classList.add('d-none');
    document.getElementById('contentState').classList.add('d-none');

    fetch(`/aplikasi/detail/${nama}`)
        .then(response => response.json())
        .then(data => {
            console.log('Data dari server:', data);
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('contentState').classList.remove('d-none');

            // Perbaikan cara menampilkan data aplikasi
            const detailTable = document.getElementById('detailTable');
            const aplikasi = data.aplikasi;
            detailTable.innerHTML = `
                <tr><th>NAMA</th><td>${aplikasi.nama}</td></tr>
                <tr><th>OPD</th><td>${aplikasi.opd}</td></tr>
                <tr><th>URAIAN</th><td>${aplikasi.uraian || '-'}</td></tr>
                <tr><th>TAHUN PEMBUATAN</th><td>${aplikasi.tahun_pembuatan || '-'}</td></tr>
                <tr><th>JENIS</th><td>${aplikasi.jenis}</td></tr>
                <tr><th>BASIS APLIKASI</th><td>${aplikasi.basis_aplikasi}</td></tr>
                <tr><th>BAHASA/FRAMEWORK</th><td>${aplikasi.bahasa_framework || '-'}</td></tr>
                <tr><th>DATABASE</th><td>${aplikasi.database || '-'}</td></tr>
                <tr><th>PENGEMBANG</th><td>${aplikasi.pengembang}</td></tr>
                <tr><th>LOKASI SERVER</th><td>${aplikasi.lokasi_server}</td></tr>
                <tr><th>STATUS PEMAKAIAN</th><td>${aplikasi.status_pemakaian}</td></tr>
            `;

            // Perbaikan cara menampilkan atribut
            const atributTable = document.getElementById('atributTable');
            const noAtributMessage = document.getElementById('noAtributMessage');
            
            if (data.atribut && data.atribut.length > 0) {
                atributTable.innerHTML = data.atribut.map(item => `
                    <tr>
                        <td>${item.nama_atribut}</td>
                        <td>${item.nilai_atribut || '-'}</td>
                    </tr>
                `).join('');
                atributTable.parentElement.classList.remove('d-none');
                noAtributMessage.classList.add('d-none');
            } else {
                atributTable.parentElement.classList.add('d-none');
                noAtributMessage.classList.remove('d-none');
            }

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        })
        .catch(error => {
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('errorState').classList.remove('d-none');
            toastr.error('Gagal mengambil detail aplikasi', 'Error');
        });
}

// Fungsi untuk reset form
function resetForm() {
    const form = $('#appForm');
    form[0].reset();
    form.attr('action', '/aplikasi');
    
    // Hapus input method jika ada
    $('#_method').remove();
    
    // Reset error alert jika ada
    $('#errorAlert').addClass('d-none');
}

// Update fungsi addApp
function addApp() {
    resetForm(); // Reset form terlebih dahulu
    $('#modalTitle').text('Tambah Aplikasi');
    $('#appForm').attr('action', '/aplikasi');
    $('#appModal').modal('show');
}

// Update fungsi editApp
function editApp(nama) {
    resetForm();
    
    $.ajax({
        url: `/aplikasi/edit/${nama}`,
        method: 'GET',
        success: function(data) {
            $('#modalTitle').text('Edit Aplikasi');
            $('#nama').val(data.nama);
            
            if (!$('#original_nama').length) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'original_nama',
                    id: 'original_nama',
                    value: data.nama
                }).appendTo('#appForm');
            } else {
                $('#original_nama').val(data.nama);
            }
            
            $('#opd').val(data.opd);
            $('#uraian').val(data.uraian || '');
            $('#tahun_pembuatan').val(data.tahun_pembuatan || '');
            $('#jenis').val(data.jenis);
            $('#basis_aplikasi').val(data.basis_aplikasi);
            $('#bahasa_framework').val(data.bahasa_framework);
            $('#database').val(data.database);
            $('#pengembang').val(data.pengembang);
            $('#lokasi_server').val(data.lokasi_server);
            $('#status_pemakaian').val(data.status_pemakaian);

            $('#appForm').attr('action', `/aplikasi/${nama}`);
            
            if (!$('#_method').length) {
                $('<input>').attr({
                    type: 'hidden',
                    name: '_method',
                    id: '_method',
                    value: 'PUT'
                }).appendTo('#appForm');
            }

            $('#appModal').modal('show');
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Gagal mengambil data aplikasi',
                icon: 'error'
            });
        }
    });
}

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
document.addEventListener('DOMContentLoaded', function() {
    showFlashMessages();
});

// Event handler untuk form submission
$('#appForm').on('submit', function(e) {
    e.preventDefault();
    
    const loadingOverlay = $('<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">')
        .append('<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>');
    $('body').append(loadingOverlay);
    
    const isEdit = $('#_method').length > 0;
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadingOverlay.remove();
            $('#appModal').modal('hide');
            
            // Simpan pesan ke localStorage
            localStorage.setItem('flash_message', isEdit ? 'Data aplikasi berhasil diperbarui!' : 'Data aplikasi berhasil ditambahkan!');
            localStorage.setItem('flash_type', 'success');
            
            window.location.href = '/aplikasi';
        },
        error: function(xhr) {
            loadingOverlay.remove();
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = '<ul class="m-0">';
                Object.values(errors).forEach(error => {
                    errorMessage += `<li>${error[0]}</li>`;
                });
                errorMessage += '</ul>';
                
                Swal.fire({
                    title: 'Validasi Gagal',
                    html: errorMessage,
                    icon: 'error'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan data',
                    icon: 'error'
                });
            }
        }
    });
});

// Tambahkan ini di bagian atas file atau setelah document ready
$(document).ready(function() {
    // Cek apakah ada flash message di localStorage
    const flashMessage = localStorage.getItem('flash_message');
    const flashType = localStorage.getItem('flash_type');
    
    if (flashMessage) {
        // Tampilkan SweetAlert
        Swal.fire({
            title: flashType === 'success' ? 'Berhasil!' : 'Error!',
            text: flashMessage,
            icon: flashType,
            timer: 1500,
            showConfirmButton: false
        });
        
        // Hapus flash message dari localStorage
        localStorage.removeItem('flash_message');
        localStorage.removeItem('flash_type');
    }
});

// Update fungsi deleteApp
function deleteApp(appId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data aplikasi akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const loadingOverlay = $('<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">')
                .append('<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>');
            $('body').append(loadingOverlay);

            $.ajax({
                url: `/aplikasi/delete/${appId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadingOverlay.remove();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data aplikasi telah dihapus',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/aplikasi';
                    });
                },
                error: function(xhr) {
                    loadingOverlay.remove();
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus data',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/aplikasi';
                    });
                }
            });
        }
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchApp').addEventListener('input', filterApps);
    document.getElementById('statusFilter').addEventListener('change', filterApps);

    if (!isCardView) {
        setupPagination();
        showTablePage();
    }
});

// Reset form saat modal ditutup
$('#appModal').on('hidden.bs.modal', function () {
    resetForm();
});

// Fungsi pencarian
$('#searchApp').on('input', function() {
    const searchTerm = $(this).val().toLowerCase();
    
    // Pencarian untuk tampilan grid
    $('#appGrid .app-card').each(function() {
        const cardText = $(this).text().toLowerCase();
        $(this).toggle(cardText.includes(searchTerm));
    });
    
    // Pencarian untuk tampilan tabel
    $('#appTable tbody tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(searchTerm));
    });
});

// Filter status
$('#statusFilter').on('change', function() {
    const status = $(this).val();
    
    // Filter untuk tampilan grid
    $('#appGrid .app-card').each(function() {
        if (!status || $(this).data('status') === status) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    // Filter untuk tampilan tabel
    $('#appTable tbody tr').each(function() {
        const rowStatus = $(this).find('.status-badge').hasClass('status-active') ? 'active' : 'unused';
        if (!status || rowStatus === status) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Fungsi untuk memuat detail atribut
function loadAtributDetail(atributId) {
    $.get(`/atribut/${atributId}/detail`, function(data) {
        let html = '';
        data.aplikasis.forEach(aplikasi => {
            html += `
                <tr>
                    <td>${aplikasi.nama}</td>
                    <td>
                        <form class="update-nilai-form" data-aplikasi-id="${aplikasi.id_aplikasi}" data-atribut-id="${atributId}">
                            <input type="text" class="form-control form-control-sm" 
                                   value="${aplikasi.pivot.nilai_atribut || ''}" 
                                   name="nilai_atribut">
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary save-nilai">Simpan</button>
                    </td>
                </tr>
            `;
        });
        $('#detailAtributContent').html(html);
    });
}

// Event handler untuk modal
$('#detailAtributModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const atributId = button.data('atribut-id');
    loadAtributDetail(atributId);
});

// Event handler untuk menyimpan nilai
$(document).on('click', '.save-nilai', function() {
    const form = $(this).closest('tr').find('form');
    const aplikasiId = form.data('aplikasi-id');
    const atributId = form.data('atribut-id');
    const nilai = form.find('input[name="nilai_atribut"]').val();

    $.ajax({
        url: `/atribut/${atributId}`,
        method: 'PUT',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_aplikasi: aplikasiId,
            nilai_atribut: nilai
        },
        success: function(response) {
            toastr.success('Nilai berhasil diperbarui');
        },
        error: function() {
            toastr.error('Gagal memperbarui nilai');
        }
    });
}); 