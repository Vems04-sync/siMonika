document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 10;
    let currentPage = 1;
    const tableBody = document.querySelector('.table tbody');
    const paginationContainer = document.querySelector('.pagination-container');
    let allRows = [];

    // Ambil semua data dari tabel
    function initializePagination() {
        allRows = Array.from(tableBody.querySelectorAll('tr'));
        displayRows();
        setupPagination();
    }

    // Tampilkan baris sesuai halaman yang aktif
    function displayRows() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        
        // Sembunyikan semua baris
        allRows.forEach(row => row.style.display = 'none');
        
        // Tampilkan baris untuk halaman yang aktif
        allRows.slice(start, end).forEach(row => row.style.display = '');
    }

    // Buat tombol-tombol paginasi
    function setupPagination() {
        const pageCount = Math.ceil(allRows.length / itemsPerPage);
        let paginationHTML = `
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="prev">Sebelumnya</a>
                    </li>
        `;

        for (let i = 1; i <= pageCount; i++) {
            paginationHTML += `
                <li class="page-item ${currentPage === i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        paginationHTML += `
                <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="next">Selanjutnya</a>
                </li>
            </ul>
        </nav>
        `;

        paginationContainer.innerHTML = paginationHTML;

        // Tambahkan event listeners
        paginationContainer.querySelectorAll('.page-link').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.dataset.page;
                
                if (page === 'prev' && currentPage > 1) {
                    currentPage--;
                } else if (page === 'next' && currentPage < pageCount) {
                    currentPage++;
                } else if (page !== 'prev' && page !== 'next') {
                    currentPage = parseInt(page);
                }

                displayRows();
                setupPagination();
            });
        });
    }

    // Inisialisasi paginasi
    initializePagination();
}); 