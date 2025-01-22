// Toggle View Handlerf

let isCardView = true;
const toggleViewBtn = document.getElementById("toggleView");
const appGrid = document.getElementById("appGrid");
const appTable = document.getElementById("appTable");

toggleViewBtn.addEventListener("click", function () {
    isCardView = !isCardView;

    if (isCardView) {
        appGrid.style.display = "flex";
        appTable.style.display = "none";
        toggleViewBtn.innerHTML =
            '<i class="bi bi-grid"></i><span class="ms-2">Ubah Tampilan</span>';
    } else {
        appGrid.style.display = "none";
        appTable.style.display = "block";
        toggleViewBtn.innerHTML =
            '<i class="bi bi-card-list"></i><span class="ms-2">Ubah Tampilan</span>';
        currentPage = 1; // Reset ke halaman pertama
        showTablePage(); // Ini akan memanggil setupPagination()
    }
});

// Pagination Configuration
const itemsPerPage = 10;
let currentPage = 1;

function setupPagination(totalRows) {
    const pageCount = Math.ceil(totalRows / itemsPerPage);

    // Pastikan currentPage valid
    if (currentPage > pageCount) {
        currentPage = pageCount || 1;
    }

    let paginationContainer = document.querySelector("#tablePagination");
    if (!paginationContainer) {
        paginationContainer = document.createElement("div");
        paginationContainer.id = "tablePagination";
        paginationContainer.className = "d-flex justify-content-end mt-3";
        document.querySelector("#appTable").appendChild(paginationContainer);
    }

    // Buat HTML pagination
    let paginationHtml = '<ul class="pagination">';
    
    // Tombol Previous
    paginationHtml += `
        <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;

    // Tampilkan semua nomor halaman jika total halaman <= 7
    if (pageCount <= 7) {
        for (let i = 1; i <= pageCount; i++) {
            paginationHtml += `
                <li class="page-item ${currentPage === i ? "active" : ""}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
    } else {
        // Logika untuk pagination dengan ellipsis
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(pageCount, startPage + 4);
        
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        // Selalu tampilkan halaman pertama
        paginationHtml += `
            <li class="page-item ${currentPage === 1 ? "active" : ""}">
                <a class="page-link" href="#" data-page="1">1</a>
            </li>
        `;

        if (startPage > 2) {
            paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }

        // Tampilkan halaman di tengah
        for (let i = startPage; i <= endPage; i++) {
            if (i !== 1 && i !== pageCount) {
                paginationHtml += `
                    <li class="page-item ${currentPage === i ? "active" : ""}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }
        }

        if (endPage < pageCount - 1) {
            paginationHtml += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }

        // Selalu tampilkan halaman terakhir
        if (pageCount > 1) {
            paginationHtml += `
                <li class="page-item ${currentPage === pageCount ? "active" : ""}">
                    <a class="page-link" href="#" data-page="${pageCount}">${pageCount}</a>
                </li>
            `;
        }
    }

    // Tombol Next
    paginationHtml += `
        <li class="page-item ${currentPage === pageCount ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>`;

    paginationContainer.innerHTML = paginationHtml;

    // Event listeners untuk pagination
    paginationContainer.querySelectorAll(".page-link").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const newPage = parseInt(link.dataset.page);
            if (!isNaN(newPage) && newPage >= 1 && newPage <= pageCount) {
                currentPage = newPage;
                showTablePage();
            }
        });
    });
}

function showTablePage() {
    const table = document.querySelector("#appTable tbody");
    const rows = Array.from(table.querySelectorAll("tr"));
    
    // Filter baris yang seharusnya terlihat berdasarkan filter yang aktif
    const visibleRows = rows.filter(row => {
        const title = row.querySelector("td:first-child").textContent.toLowerCase();
        const status = row.querySelector(".status-badge").textContent.trim().toLowerCase() === "aktif" ? "active" : "unused";
        const jenis = row.cells[4].textContent.trim();
        const basis = row.cells[5].textContent.trim();
        const bahasa = row.cells[6].textContent.trim();
        const database = row.cells[7].textContent.trim();
        const pengembang = row.cells[8].textContent.trim();
        const lokasi = row.cells[9].textContent.trim();

        const searchTerm = document.getElementById("searchApp").value.toLowerCase();
        const statusFilter = document.getElementById("statusFilter").value;
        const jenisFilter = document.getElementById("jenisFilter").value;
        const basisFilter = document.getElementById("basisFilter").value;
        const bahasaFilter = document.getElementById("bahasaFilter").value;
        const databaseFilter = document.getElementById("databaseFilter").value;
        const pengembangFilter = document.getElementById("pengembangFilter").value;
        const lokasiFilter = document.getElementById("lokasiFilter").value;

        const matchesSearch = !searchTerm || title.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesJenis = !jenisFilter || jenis.toLowerCase() === jenisFilter.toLowerCase();
        const matchesBasis = !basisFilter || basis.toLowerCase().includes(basisFilter.toLowerCase());
        const matchesBahasa = !bahasaFilter || bahasa.toLowerCase() === bahasaFilter.toLowerCase();
        const matchesDatabase = !databaseFilter || database.toLowerCase() === databaseFilter.toLowerCase();
        const matchesPengembang = !pengembangFilter || pengembang.toLowerCase() === pengembangFilter.toLowerCase();
        const matchesLokasi = !lokasiFilter || lokasi.toLowerCase() === lokasiFilter.toLowerCase();

        return matchesSearch && matchesStatus && matchesJenis && matchesBasis && 
               matchesBahasa && matchesDatabase && matchesPengembang && matchesLokasi;
    });

    // Sembunyikan semua baris terlebih dahulu
    rows.forEach(row => {
        row.style.display = "none";
    });

    // Hitung start dan end index untuk halaman saat ini
    const start = (currentPage - 1) * itemsPerPage;
    const end = Math.min(start + itemsPerPage, visibleRows.length);

    // Tampilkan baris yang sesuai dengan halaman saat ini
    for (let i = start; i < end; i++) {
        if (visibleRows[i]) {
            visibleRows[i].style.display = "";
            // Update nomor urut
            const numberCell = visibleRows[i].cells[0];
            if (numberCell) {
                numberCell.textContent = i + 1;
            }
        }
    }

    // Update pagination
    setupPagination(visibleRows.length);
}

function filterApps() {
    const searchTerm = document.getElementById("searchApp").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value;
    const jenisFilter = document.getElementById("jenisFilter").value;
    const basisFilter = document.getElementById("basisFilter").value;
    const bahasaFilter = document.getElementById("bahasaFilter").value;
    const databaseFilter = document.getElementById("databaseFilter").value;
    const pengembangFilter = document.getElementById("pengembangFilter").value;
    const lokasiFilter = document.getElementById("lokasiFilter").value;

    // Filter cards
    const cards = document.querySelectorAll(".app-card");
    cards.forEach((card) => {
        const title = card
            .querySelector(".card-title")
            .textContent.toLowerCase();
        const status = card.dataset.status;
        const jenis = card.dataset.jenis;
        const basis = card.dataset.basis;
        const bahasa = card.dataset.bahasa;
        const database = card.dataset.database;
        const pengembang = card.dataset.pengembang;
        const lokasi = card.dataset.lokasi;

        const matchesSearch = !searchTerm || title.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesJenis =
            !jenisFilter || jenis.toLowerCase() === jenisFilter.toLowerCase();
        const matchesBasis =
            !basisFilter || basis.toLowerCase() === basisFilter.toLowerCase();
        const matchesBahasa =
            !bahasaFilter ||
            bahasa.toLowerCase() === bahasaFilter.toLowerCase();
        const matchesDatabase =
            !databaseFilter ||
            database.toLowerCase() === databaseFilter.toLowerCase();
        const matchesPengembang =
            !pengembangFilter ||
            pengembang.toLowerCase() === pengembangFilter.toLowerCase();
        const matchesLokasi =
            !lokasiFilter ||
            lokasi.toLowerCase() === lokasiFilter.toLowerCase();

        if (
            matchesSearch &&
            matchesStatus &&
            matchesJenis &&
            matchesBasis &&
            matchesBahasa &&
            matchesDatabase &&
            matchesPengembang &&
            matchesLokasi
        ) {
            card.closest(".col-md-6").style.display = "";
        } else {
            card.closest(".col-md-6").style.display = "none";
        }
    });

    // Filter table rows
    const rows = document.querySelectorAll("#appTable tbody tr");
    rows.forEach((row) => {
        // Mengambil nilai dari sel tabel dengan lebih tepat
        const appName = row.cells[0].textContent.toLowerCase();
        const status =
            row
                .querySelector(".status-badge")
                .textContent.trim()
                .toLowerCase() === "aktif"
                ? "active"
                : "unused";

        // Perbaikan cara mengambil nilai dari sel tabel
        const jenis = row.cells[4].textContent.trim(); // Kolom Jenis
        const basis =
            row.cells[5].querySelector("i")?.nextSibling?.textContent.trim() ||
            row.cells[5].textContent.trim(); // Kolom Basis Aplikasi
        const bahasa = row.cells[6].textContent.trim(); // Kolom Bahasa/Framework
        const database = row.cells[7].textContent.trim(); // Kolom Database
        const pengembang = row.cells[8].textContent.trim(); // Kolom Pengembang
        const lokasi = row.cells[9].textContent.trim(); // Kolom Lokasi Server

        const matchesSearch = !searchTerm || appName.includes(searchTerm);
        const matchesStatus =
            !statusFilter ||
            (statusFilter === "active" && status === "active") ||
            (statusFilter === "unused" && status === "unused");

        // Perbandingan yang lebih tepat untuk setiap filter
        const matchesJenis =
            !jenisFilter ||
            jenis.toLowerCase().includes(jenisFilter.toLowerCase());
        const matchesBasis =
            !basisFilter ||
            basis.toLowerCase().includes(basisFilter.toLowerCase());
        const matchesBahasa =
            !bahasaFilter ||
            bahasa.toLowerCase().includes(bahasaFilter.toLowerCase());
        const matchesDatabase =
            !databaseFilter ||
            database.toLowerCase().includes(databaseFilter.toLowerCase());
        const matchesPengembang =
            !pengembangFilter ||
            pengembang.toLowerCase().includes(pengembangFilter.toLowerCase());
        const matchesLokasi =
            !lokasiFilter ||
            lokasi.toLowerCase().includes(lokasiFilter.toLowerCase());

        const shouldShow =
            matchesSearch &&
            matchesStatus &&
            matchesJenis &&
            matchesBasis &&
            matchesBahasa &&
            matchesDatabase &&
            matchesPengembang &&
            matchesLokasi;

        row.style.display = shouldShow ? "" : "none";
    });

    // Reset dan update pagination setelah filtering
    if (!isCardView) {
        currentPage = 1; // Reset ke halaman pertama
        setupPagination();
        showTablePage();
    }
}

// Event listeners untuk semua filter
document.addEventListener("DOMContentLoaded", function () {
    const filters = [
        "statusFilter",
        "jenisFilter",
        "basisFilter",
        "bahasaFilter",
        "databaseFilter",
        "pengembangFilter",
        "lokasiFilter",
    ];

    // Tambahkan event listener untuk setiap filter
    filters.forEach((filterId) => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener("change", filterApps);
        }
    });

    // Event listener untuk search dengan debounce
    let searchTimeout;
    const searchInput = document.getElementById("searchApp");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterApps, 300);
        });
    }

    // Inisialisasi filter saat halaman dimuat
    filterApps();
});

// CRUD Operations
// Hapus atau komentar fungsi viewAppDetails yang lama

// Helper function to format label
function formatLabel(key) {
    const labels = {
        nama: "Nama",
        opd: "OPD",
        uraian: "Uraian",
        tahun_pembuatan: "Tahun Pembuatan",
        jenis: "Jenis",
        basis_aplikasi: "Basis Aplikasi",
        bahasa_framework: "Bahasa/Framework",
        database: "Database",
        pengembang: "Pengembang",
        lokasi_server: "Lokasi Server",
        status_pemakaian: "Status Pemakaian",
    };
    return labels[key] || key;
}

// Fungsi untuk reset form
function resetForm() {
    const form = $("#appForm");
    form[0].reset();
    form.attr("action", "/aplikasi");

    // Hapus input method jika ada
    $("#_method").remove();

    // Reset error alert jika ada
    $("#errorAlert").addClass("d-none");
}

// Update fungsi addApp
function addApp() {
    resetForm(); // Reset form terlebih dahulu
    $("#modalTitle").text("Tambah Aplikasi");
    $("#appForm").attr("action", "/aplikasi");
    $("#appModal").modal("show");
}

// Fungsi untuk edit aplikasi
function editApp(nama) {
    // Reset form dan hapus error messages
    resetForm();

    // Tampilkan loading
    const loadingOverlay = $(
        '<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">'
    ).append(
        '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>'
    );
    $("body").append(loadingOverlay);

    // Ambil data aplikasi
    $.ajax({
        url: `/aplikasi/${nama}/edit`,
        method: "GET",
        success: function (response) {
            loadingOverlay.remove();

            // Isi form dengan data yang ada
            $("#nama").val(response.nama);
            $("#opd").val(response.opd);
            $("#uraian").val(response.uraian);
            $("#tahun_pembuatan").val(response.tahun_pembuatan);
            $("#jenis").val(response.jenis);
            $("#basis_aplikasi").val(response.basis_aplikasi);
            $("#bahasa_framework").val(response.bahasa_framework);
            $("#database").val(response.database);
            $("#pengembang").val(response.pengembang);
            $("#lokasi_server").val(response.lokasi_server);
            $("#status_pemakaian").val(response.status_pemakaian);

            // Set form untuk update
            $("#modalTitle").text("Edit Aplikasi");
            $("#appForm").attr("action", `/aplikasi/${nama}`);

            // Hapus method field lama jika ada
            $("#appForm").find('input[name="_method"]').remove();
            // Tambahkan method PUT
            $("#appForm").append(
                '<input type="hidden" name="_method" value="PUT">'
            );

            // Tampilkan modal
            $("#appModal").modal("show");
        },
        error: function (xhr) {
            loadingOverlay.remove();
            toastr.error(
                xhr.responseJSON?.message || "Gagal memuat data aplikasi"
            );
        },
    });
}

// Event handler untuk tombol edit
$(document).ready(function () {
    // Event handler untuk tombol edit di kedua tampilan
    $(document).on("click", ".btn-edit", function (e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent event bubbling
        const nama = $(this).data("nama");
        editApp(nama);
    });

    // Reset form saat modal ditutup
    $("#appModal").on("hidden.bs.modal", function () {
        resetForm();
    });

    // Update fungsi untuk menangani form submission
    $("#appForm").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const isEdit = form.find('input[name="_method"]').val() === "PUT";

        // Disable button
        submitBtn.prop("disabled", true);

        // Reset error states
        form.find(".is-invalid").removeClass("is-invalid");
        form.find(".invalid-feedback").remove();

        // Show loading
        const loadingOverlay = $(
            '<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">'
        ).append(
            '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>'
        );
        $("body").append(loadingOverlay);

        $.ajax({
            url: form.attr("action"),
            method: form.find('input[name="_method"]').val() || "POST",
            data: form.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#appModal").modal("hide");
                    // Simpan pesan ke sessionStorage untuk ditampilkan setelah refresh
                    sessionStorage.setItem('flash_message', isEdit ? "Aplikasi berhasil diperbarui" : "Aplikasi berhasil ditambahkan");
                    sessionStorage.setItem('flash_type', 'success');
                    // Redirect atau refresh halaman
                    window.location.reload();
                } else {
                    toastr.error(response.message || "Terjadi kesalahan");
                    submitBtn.prop("disabled", false);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach((field) => {
                        const input = form.find(`[name="${field}"]`);
                        input.addClass("is-invalid");
                        input.after(
                            `<div class="invalid-feedback">${errors[field][0]}</div>`
                        );
                    });
                    toastr.error("Mohon periksa kembali input Anda");
                } else {
                    if (xhr.responseJSON && xhr.responseJSON.success) {
                        $("#appModal").modal("hide");
                        // Simpan pesan ke sessionStorage untuk ditampilkan setelah refresh
                        sessionStorage.setItem('flash_message', isEdit ? "Aplikasi berhasil diperbarui" : "Aplikasi berhasil ditambahkan");
                        sessionStorage.setItem('flash_type', 'success');
                        // Redirect atau refresh halaman
                        window.location.reload();
                    } else {
                        toastr.error(
                            xhr.responseJSON?.message ||
                                "Terjadi kesalahan saat menyimpan data"
                        );
                    }
                }
                submitBtn.prop("disabled", false);
            },
            complete: function () {
                loadingOverlay.remove();
            },
        });
    });
});

// Konfigurasi global toastr
toastr.options = {
    closeButton: true,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: true,
    timeOut: "3000",
};

// Fungsi untuk menampilkan flash message
function showFlashMessages() {
    const flashMessage = localStorage.getItem("flash_message");
    if (flashMessage) {
        toastr.success(flashMessage, "Berhasil");
        localStorage.removeItem("flash_message");
    }
}

// Panggil fungsi saat dokumen siap
document.addEventListener("DOMContentLoaded", function () {
    showFlashMessages();
});

// Tambahkan ini di bagian atas file atau setelah document ready
$(document).ready(function () {
    // Cek apakah ada flash message di localStorage
    const flashMessage = localStorage.getItem("flash_message");
    const flashType = localStorage.getItem("flash_type");

    if (flashMessage) {
        // Tampilkan SweetAlert
        Swal.fire({
            title: flashType === "success" ? "Berhasil!" : "Error!",
            text: flashMessage,
            icon: flashType,
            timer: 1500,
            showConfirmButton: false,
        });

        // Hapus flash message dari localStorage
        localStorage.removeItem("flash_message");
        localStorage.removeItem("flash_type");
    }
});

// Update fungsi deleteApp
function deleteApp(appId) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data aplikasi akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            const loadingOverlay = $(
                '<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">'
            ).append(
                '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>'
            );
            $("body").append(loadingOverlay);

            $.ajax({
                url: `/aplikasi/delete/${appId}`,
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    loadingOverlay.remove();
                    // Simpan pesan ke sessionStorage
                    sessionStorage.setItem('flash_message', 'Data aplikasi berhasil dihapus');
                    sessionStorage.setItem('flash_type', 'success');
                    window.location.reload();
                },
                error: function (xhr) {
                    loadingOverlay.remove();
                    // Simpan pesan error ke sessionStorage
                    sessionStorage.setItem('flash_message', 'Gagal menghapus data aplikasi');
                    sessionStorage.setItem('flash_type', 'error');
                    window.location.reload();
                },
            });
        }
    });
}

// Event Listeners
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("searchApp").addEventListener("input", filterApps);
    document
        .getElementById("statusFilter")
        .addEventListener("change", filterApps);

    if (!isCardView) {
        setupPagination();
        showTablePage();
    }
});

// Reset form saat modal ditutup
$("#appModal").on("hidden.bs.modal", function () {
    resetForm();
});

// Fungsi pencarian
$("#searchApp").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();

    // Pencarian untuk tampilan grid
    $("#appGrid .app-card").each(function () {
        const cardText = $(this).text().toLowerCase();
        $(this).toggle(cardText.includes(searchTerm));
    });

    // Pencarian untuk tampilan tabel
    $("#appTable tbody tr").each(function () {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(searchTerm));
    });
});

// Filter status
$("#statusFilter").on("change", function () {
    const status = $(this).val();

    // Filter untuk tampilan grid
    $("#appGrid .app-card").each(function () {
        if (!status || $(this).data("status") === status) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    // Filter untuk tampilan tabel
    $("#appTable tbody tr").each(function () {
        const rowStatus = $(this)
            .find(".status-badge")
            .hasClass("status-active")
            ? "active"
            : "unused";
        if (!status || rowStatus === status) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Fungsi untuk memuat detail atribut
function loadAtributDetail(atributId) {
    $.get(`/atribut/${atributId}/detail`, function (data) {
        let html = "";
        data.aplikasis.forEach((aplikasi) => {
            html += `
                <tr>
                    <td>${aplikasi.nama}</td>
                    <td>
                        <form class="update-nilai-form" data-aplikasi-id="${
                            aplikasi.id_aplikasi
                        }" data-atribut-id="${atributId}">
                            <input type="text" class="form-control form-control-sm" 
                                   value="${
                                       aplikasi.pivot.nilai_atribut || ""
                                   }" 
                                   name="nilai_atribut">
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary save-nilai">Simpan</button>
                    </td>
                </tr>
            `;
        });
        $("#detailAtributContent").html(html);
    });
}

// Event handler untuk modal
$("#detailAtributModal").on("show.bs.modal", function (event) {
    const button = $(event.relatedTarget);
    const atributId = button.data("atribut-id");
    loadAtributDetail(atributId);
});

// Event handler untuk menyimpan nilai
$(document).on("click", ".save-nilai", function () {
    const form = $(this).closest("tr").find("form");
    const aplikasiId = form.data("aplikasi-id");
    const atributId = form.data("atribut-id");
    const nilai = form.find('input[name="nilai_atribut"]').val();

    $.ajax({
        url: `/atribut/${atributId}`,
        method: "PUT",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id_aplikasi: aplikasiId,
            nilai_atribut: nilai,
        },
        success: function (response) {
            toastr.success("Nilai berhasil diperbarui");
        },
        error: function () {
            toastr.error("Gagal memperbarui nilai");
        },
    });
});

// Tambahkan event listener untuk tombol detail
$(document).ready(function () {
    // Event handler untuk tombol detail (baik di tabel maupun card)
    $(document).on('click', '.btn-detail, .card-detail-btn', function(e) {
        e.preventDefault();
        const nama = $(this).data('nama');
        
        // Tampilkan loading state
        const loadingOverlay = $('<div class="position-fixed w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); top: 0; left: 0; z-index: 9999;">')
            .append('<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>');
        $('body').append(loadingOverlay);
        
        // Ajax request untuk detail aplikasi
        $.ajax({
            url: `/aplikasi/detail/${nama}`,
            method: 'GET',
            success: function(response) {
                const app = response.aplikasi;
                
                // Update informasi aplikasi
                $('#detail-nama').text(app.nama);
                $('#detail-opd').text(app.opd);
                $('#detail-uraian').text(app.uraian);
                $('#detail-tahun').text(app.tahun_pembuatan);
                $('#detail-jenis').text(app.jenis);
                $('#detail-basis').text(app.basis_aplikasi);
                $('#detail-bahasa').text(app.bahasa_framework);
                $('#detail-database').text(app.database);
                $('#detail-pengembang').text(app.pengembang);
                $('#detail-server').text(app.lokasi_server);
                $('#detail-status').text(app.status_pemakaian);
                
                // Update atribut tambahan
                let atributHtml = '<table class="table table-borderless">';
                if (response.atribut_tambahan && response.atribut_tambahan.length > 0) {
                    response.atribut_tambahan.forEach(atribut => {
                        atributHtml += `
                            <tr>
                                <td width="40%">${atribut.nama_atribut}</td>
                                <td>${atribut.nilai_atribut || '-'}</td>
                            </tr>`;
                    });
                } else {
                    atributHtml += '<tr><td colspan="2">Tidak ada atribut tambahan</td></tr>';
                }
                atributHtml += '</table>';
                $('#atribut-tambahan-content').html(atributHtml);
                
                // Tampilkan modal
                $('#detailAplikasiModal').modal('show');
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                // Hapus notifikasi error
            },
            complete: function() {
                loadingOverlay.remove();
            }
        });
    });
});

// Form submit handler untuk edit
$('#editForm').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const url = form.attr('action');
    
    $.ajax({
        url: url,
        method: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#editModal').modal('hide');
                // Simpan pesan ke sessionStorage untuk ditampilkan setelah refresh
                sessionStorage.setItem('flash_message', 'Data aplikasi berhasil diperbarui');
                sessionStorage.setItem('flash_type', 'success');
                window.location.reload();
            }
        },
        error: function(xhr) {
            // Simpan pesan error ke sessionStorage
            sessionStorage.setItem('flash_message', 'Gagal memperbarui data aplikasi');
            sessionStorage.setItem('flash_type', 'error');
            window.location.reload();
        }
    });
});

// Tambahkan event listener untuk document ready
$(document).ready(function() {
    // Cek flash message dari sessionStorage
    const flashMessage = sessionStorage.getItem('flash_message');
    const flashType = sessionStorage.getItem('flash_type');
    
    if (flashMessage) {
        // Tampilkan notifikasi
        toastr[flashType || 'success'](flashMessage);
        
        // Hapus flash message dari sessionStorage
        sessionStorage.removeItem('flash_message');
        sessionStorage.removeItem('flash_type');
    }
});
