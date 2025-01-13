// Toggle View Handler
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
    const rows = table.querySelectorAll('tr');
    const pageCount = Math.ceil(rows.length / itemsPerPage);

    let paginationContainer = document.querySelector('#tablePagination');
    if (!paginationContainer) {
        paginationContainer = document.createElement('div');
        paginationContainer.id = 'tablePagination';
        paginationContainer.className = 'd-flex justify-content-end mt-3';
        document.querySelector('#appTable').appendChild(paginationContainer);
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
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => row.style.display = 'none');
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    for (let i = start; i < end && i < rows.length; i++) {
        rows[i].style.display = '';
    }
}

function filterApps() {
    const searchTerm = document.getElementById('searchApp').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;

    // Filter cards
    const cards = document.querySelectorAll('.app-card');
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const status = card.dataset.status;
        const matchesSearch = title.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        card.style.display = matchesSearch && matchesStatus ? 'block' : 'none';
    });

    // Filter table rows
    const rows = document.querySelectorAll('#appTable tbody tr');
    let visibleRows = [];

    rows.forEach(row => {
        const title = row.cells[0].textContent.toLowerCase();
        const status = row.cells[2].textContent.trim().toLowerCase() === 'aktif' ? 'active' : 'unused';
        const matchesSearch = title.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            visibleRows.push(row);
        }
        row.style.display = 'none';
    });

    currentPage = 1;
    const start = 0;
    const end = Math.min(itemsPerPage, visibleRows.length);
    for (let i = start; i < end; i++) {
        visibleRows[i].style.display = '';
    }

    if (searchTerm || statusFilter) {
        const paginationContainer = document.querySelector('#tablePagination');
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
    } else {
        setupPagination();
        const paginationContainer = document.querySelector('#tablePagination');
        if (paginationContainer) {
            paginationContainer.style.display = '';
        }
    }
}

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
            document.getElementById('errorMessage').textContent = 'Terjadi kesalahan saat mengambil data';
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
function editApp(appId) {
    resetForm(); // Reset form terlebih dahulu
    
    $.ajax({
        url: `/aplikasi/edit/${appId}`,
        method: 'GET',
        success: function(data) {
            $('#modalTitle').text('Edit Aplikasi');

            // Isi form dengan data yang ada
            $('#nama').val(data.nama);
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

            // Set form untuk mode edit
            $('#appForm').attr('action', `/aplikasi/${appId}`);
            
            // Tambah method field untuk PUT
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
        error: function(xhr) {
            alert('Terjadi kesalahan saat mengambil data aplikasi');
        }
    });
}

// Konfigurasi default untuk toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Update event handler untuk form submission
$('#appForm').on('submit', function(e) {
    e.preventDefault();
    
    // Tampilkan loading overlay
    const loadingOverlay = $('<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">')
        .append('<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>');
    $('body').append(loadingOverlay);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Sembunyikan loading overlay
            loadingOverlay.remove();
            
            // Tutup modal
            $('#appModal').modal('hide');
            
            // Tampilkan notifikasi sukses
            toastr.success(
                $('#_method').val() === 'PUT' ? 
                'Data aplikasi berhasil diperbarui!' : 
                'Data aplikasi berhasil ditambahkan!'
            );
            
            // Refresh halaman setelah notifikasi
            setTimeout(() => {
                location.reload();
            }, 2000);
        },
        error: function(xhr) {
            // Sembunyikan loading overlay
            loadingOverlay.remove();
            
            if (xhr.status === 422) {
                // Tampilkan error validasi
                toastr.error(xhr.responseJSON.message);
                $('#errorAlert')
                    .removeClass('d-none')
                    .find('#errorMessage')
                    .text(xhr.responseJSON.message);
            } else {
                // Tampilkan error umum
                toastr.error('Terjadi kesalahan saat menyimpan data');
                $('#errorAlert')
                    .removeClass('d-none')
                    .find('#errorMessage')
                    .text('Terjadi kesalahan saat menyimpan data');
            }
        }
    });
});

// Update fungsi deleteApp
function deleteApp(appId) {
    if (confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')) {
        // Tampilkan loading overlay
        const loadingOverlay = $('<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">')
            .append('<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>');
        $('body').append(loadingOverlay);

        $.ajax({
            url: `/aplikasi/delete/${appId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                // Sembunyikan loading overlay
                loadingOverlay.remove();
                
                // Tampilkan notifikasi sukses
                toastr.success('Data aplikasi berhasil dihapus!');
                
                // Refresh halaman setelah notifikasi
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function() {
                // Sembunyikan loading overlay
                loadingOverlay.remove();
                
                // Tampilkan notifikasi error
                toastr.error('Terjadi kesalahan saat menghapus data');
            }
        });
    }
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