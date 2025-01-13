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

function addApp() {
    document.getElementById('modalTitle').textContent = 'Tambah Aplikasi';
    const modal = new bootstrap.Modal(document.getElementById('appModal'));
    modal.show();
}

function editApp(appId) {
    fetch(`/aplikasi/edit/${appId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Aplikasi tidak ditemukan');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Aplikasi';

            document.getElementById('nama').value = data.nama;
            document.getElementById('opd').value = data.opd;
            document.getElementById('uraian').value = data.uraian;
            document.getElementById('tahun_pembuatan').value = data.tahun_pembuatan;
            document.getElementById('jenis').value = data.jenis;
            document.getElementById('basis_aplikasi').value = data.basis_aplikasi;
            document.getElementById('bahasa_framework').value = data.bahasa_framework;
            document.getElementById('database').value = data.database;
            document.getElementById('pengembang').value = data.pengembang;
            document.getElementById('lokasi_server').value = data.lokasi_server;
            document.getElementById('status_pemakaian').value = data.status_pemakaian;

            const form = document.getElementById('appForm');
            form.action = `/aplikasi/${appId}`;
            form.method = 'POST';

            let methodField = document.getElementById('_method');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.id = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';

            const modal = new bootstrap.Modal(document.getElementById('appModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data aplikasi');
        });
}

function deleteApp(appId) {
    if (confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/aplikasi/delete/${appId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
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