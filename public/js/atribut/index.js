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

// Fungsi untuk menampilkan detail aplikasi
window.showAppDetail = function (id) {
    $.ajax({
        url: `/aplikasi/${id}`,
        method: "GET",
        success: function (response) {
            const app = response; // Sesuaikan dengan struktur response

            // Update informasi dasar aplikasi
            $("#detail-nama").text(app.nama);
            $("#detail-opd").text(app.opd);
            $("#detail-status").html(`
                <span class="badge ${
                    app.status_pemakaian === "Aktif"
                        ? "bg-success"
                        : "bg-danger"
                }">
                    ${app.status_pemakaian}
                </span>
            `);
            $("#detail-pengembang").text(app.pengembang);

            // Update atribut tambahan
            let atributHtml = '<table class="table">';
            atributHtml += `
                <thead>
                    <tr>
                        <th>Nama Atribut</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
            `;

            if (app.atribut_tambahans && app.atribut_tambahans.length > 0) {
                app.atribut_tambahans.forEach((atribut) => {
                    atributHtml += `
                        <tr>
                            <td>${atribut.nama_atribut}</td>
                            <td>${atribut.pivot.nilai_atribut || "-"}</td>
                        </tr>
                    `;
                });
            } else {
                atributHtml += `
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada atribut tambahan</td>
                    </tr>
                `;
            }

            atributHtml += "</tbody></table>";
            $("#detail-atribut").html(atributHtml);

            $("#detailAppModal").modal("show");
        },
        error: function () {
            toastr.error("Gagal memuat detail aplikasi");
        },
    });
};

// Fungsi untuk menampilkan form edit atribut
window.editAppAtribut = function (id) {
    $.ajax({
        url: `/aplikasi/${id}/atribut`,
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (!response.success) {
                toastr.error(response.message || "Gagal memuat data atribut");
                return;
            }

            let html = "";
            const atributs = response.atribut_tambahans || [];

            atributs.forEach((atribut) => {
                const nilai = atribut.pivot ? atribut.pivot.nilai_atribut : "";

                html += `
                <div class="mb-3">
                    <label class="form-label">
                        ${atribut.nama_atribut}
                        <small class="text-muted">(${atribut.tipe_data})</small>
                    </label>`;

                switch (atribut.tipe_data) {
                    case "date":
                        html += `
                            <input type="date" 
                                   class="form-control" 
                                   name="atribut[${atribut.id_atribut}]" 
                                   value="${nilai || ""}">`;
                        break;
                    case "number":
                        html += `
                            <input type="number" 
                                   class="form-control" 
                                   name="atribut[${atribut.id_atribut}]" 
                                   value="${nilai || ""}">`;
                        break;
                    case "text":
                        html += `
                            <textarea class="form-control" 
                                      name="atribut[${atribut.id_atribut}]">${
                            nilai || ""
                        }</textarea>`;
                        break;
                    case "enum":
                        html += `<select class="form-select" name="atribut[${atribut.id_atribut}]">
                                    <option value="">Pilih Opsi</option>`;
                        if (atribut.enum_options) {
                            const options =
                                typeof atribut.enum_options === "string"
                                    ? JSON.parse(atribut.enum_options)
                                    : atribut.enum_options;

                            options.forEach((option) => {
                                html += `
                                    <option value="${option}" ${
                                    nilai === option ? "selected" : ""
                                }>
                                        ${option}
                                    </option>`;
                            });
                        }
                        html += `</select>`;
                        break;
                    default:
                        html += `
                            <input type="text" 
                                   class="form-control" 
                                   name="atribut[${atribut.id_atribut}]" 
                                   value="${nilai || ""}">`;
                }
                html += `</div>`;
            });

            $("#editAtributForm").data("app-id", id);
            $("#atributFields").html(html);
            $("#editAtributModal").modal("show");
        },
        error: function (xhr) {
            console.error("Error response:", xhr.responseText);
            toastr.error("Terjadi kesalahan saat memuat data");
        },
    });
};

function getInputType(tipeData) {
    switch (tipeData) {
        case "number":
            return "number";
        case "date":
            return "date";
        default:
            return "text";
    }
}

function getInputAttributes(tipeData) {
    switch (tipeData) {
        case "number":
            return 'step="any"';
        case "varchar":
            return 'maxlength="255"';
        default:
            return "";
    }
}

$(document).ready(function () {
    showFlashMessages();

    // Inisialisasi Select2 pada modal tambah
    $("#tambahAtributModal .select2").select2({
        theme: "bootstrap-5",
        width: "100%",
        dropdownParent: $("#tambahAtributModal"),
        placeholder: "Pilih Aplikasi",
        allowClear: true,
        language: {
            noResults: function () {
                return "Data tidak ditemukan";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    });

    // Inisialisasi Select2 pada modal edit
    $("#editAtributModal .select2").select2({
        theme: "bootstrap-5",
        width: "100%",
        dropdownParent: $("#editAtributModal"),
        placeholder: "Pilih Aplikasi",
        allowClear: true,
        language: {
            noResults: function () {
                return "Data tidak ditemukan";
            },
            searching: function () {
                return "Mencari...";
            },
        },
    });

    // Reset Select2 saat modal ditutup
    $("#tambahAtributModal").on("hidden.bs.modal", function () {
        $(".select2").val("").trigger("change");
    });

    // Handle form submit untuk delete
    $('form[method="POST"]').on("submit", function (e) {
        if ($(this).find('input[name="_method"]').val() === "DELETE") {
            e.preventDefault();
            if (confirm("Yakin ingin menghapus atribut ini?")) {
                const form = $(this);
                $.ajax({
                    url: form.attr("action"),
                    method: "POST",
                    data: form.serialize(),
                    success: function (response) {
                        // Langsung redirect tanpa menyimpan ke localStorage
                        window.location.href = "/atribut";
                    },
                    error: function (xhr) {
                        toastr.error(
                            "Terjadi kesalahan saat menghapus data",
                            "Error"
                        );
                    },
                });
            }
        }
    });

    // Handle form submit untuk tambah dan edit
    $("#tambahAtributModal form, #editAtributModal form").on(
        "submit",
        function (e) {
            e.preventDefault();
            const form = $(this);
            const isEdit = form.find('input[name="_method"]').val() === "PUT";

            $.ajax({
                url: form.attr("action"),
                method: "POST",
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        localStorage.setItem(
                            "flash_message",
                            isEdit
                                ? "Data atribut berhasil diperbarui!"
                                : "Data atribut berhasil ditambahkan!"
                        );
                        window.location.reload();
                    } else {
                        // Tampilkan pesan error dari response
                        toastr.error(response.message || "Terjadi kesalahan");
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        // Tampilkan pesan error validasi
                        if (
                            errors.nama_atribut &&
                            errors.nama_atribut.includes("already exists")
                        ) {
                            toastr.error(
                                "Nama atribut sudah ada, silakan gunakan nama lain",
                                "Validasi Gagal"
                            );
                        } else {
                            let errorMessage = '<ul class="m-0">';
                            Object.values(errors).forEach((error) => {
                                errorMessage += `<li>${error[0]}</li>`;
                            });
                            errorMessage += "</ul>";
                            toastr.error(errorMessage, "Validasi Gagal");
                        }
                    } else {
                        toastr.error("Terjadi kesalahan pada server", "Error");
                    }
                },
            });
        }
    );

    // Handle tombol edit
    $(".edit-btn").on("click", function () {
        const id = $(this).data("id");

        $.ajax({
            url: `/atribut/${id}/edit`,
            method: "GET",
            success: function (response) {
                $("#editAtributForm").attr("action", `/atribut/${id}`);
                $('#editAtributModal select[name="id_aplikasi"]')
                    .val(response.id_aplikasi)
                    .trigger("change");
                $('#editAtributModal input[name="nama_atribut"]').val(
                    response.nama_atribut
                );
                $('#editAtributModal input[name="nilai_atribut"]').val(
                    response.nilai_atribut
                );
            },
            error: function (xhr) {
                toastr.error("Gagal mengambil data atribut", "Error");
            },
        });
    });

    // Inisialisasi Select2
    $(".select2").select2({
        theme: "bootstrap-5",
    });

    // Show/hide enum options based on selected type
    $("#tipeDataSelect").on("change", function () {
        if ($(this).val() === "enum") {
            $("#enumOptionsContainer").show();
        } else {
            $("#enumOptionsContainer").hide();
            $("#enumOptions").empty(); // Clear options if not enum
        }
    });

    // Add new enum option input
    $("#addEnumOption").on("click", function () {
        $("#enumOptions").append(`
            <div class="mb-2">
                <input type="text" class="form-control" name="enum_options[]" placeholder="Masukkan opsi enum">
                <button type="button" class="btn btn-danger removeEnumOption">Hapus</button>
            </div>
        `);
    });

    // Remove enum option input
    $(document).on("click", ".removeEnumOption", function () {
        $(this).parent().remove();
    });

    // Handle form submit untuk tambah atribut
    $("#formTambahAtribut").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Jika tipe data adalah enum, tambahkan opsi enum ke formData
        if ($("#tipeDataSelect").val() === "enum") {
            const enumOptions = [];
            $('input[name="enum_options[]"]').each(function () {
                if ($(this).val().trim() !== "") {
                    enumOptions.push($(this).val().trim());
                }
            });
            formData.set("enum_options", JSON.stringify(enumOptions));
        }

        $.ajax({
            url: $(this).attr("action"),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#tambahAtributModal").modal("hide");
                    toastr.success(
                        response.message || "Atribut berhasil ditambahkan"
                    );
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(
                        response.message || "Gagal menambahkan atribut"
                    );
                }
            },
        });
    });

    // Konfigurasi Select2 untuk dropdown dengan pencarian
    $(".select2-with-search").select2({
        theme: "bootstrap-5",
        width: "100%",
        dropdownParent: $("#tambahAtributModal"),
        placeholder: "Cari dan pilih aplikasi...",
        allowClear: true,
        language: {
            noResults: function () {
                return "Aplikasi tidak ditemukan";
            },
            searching: function () {
                return "Mencari...";
            },
        },
        templateResult: formatAplikasi,
        templateSelection: formatAplikasi,
        escapeMarkup: function (markup) {
            return markup;
        },
    });

    // Format tampilan aplikasi di dropdown
    function formatAplikasi(aplikasi) {
        if (!aplikasi.id) return aplikasi.text;

        var $aplikasi = $(
            '<span><i class="bi bi-app me-2"></i>' + aplikasi.text + "</span>"
        );

        return $aplikasi;
    }

    // Reset Select2 saat modal ditutup
    $("#tambahAtributModal").on("hidden.bs.modal", function () {
        $(".select2-with-search").val("").trigger("change");
    });

    // Fungsi untuk filter tabel atribut
    function filterAtributTable() {
        const atributFilter = $("#atributFilter").val().toLowerCase();

        // Sembunyikan semua baris terlebih dahulu
        $("#tabelAtribut tbody tr").hide();

        // Filter baris tabel atribut
        $("#tabelAtribut tbody tr").each(function () {
            const namaAtribut = $(this)
                .find("td:nth-child(2)")
                .text()
                .toLowerCase();

            // Tampilkan baris jika sesuai filter atau filter kosong
            if (atributFilter === "" || namaAtribut.includes(atributFilter)) {
                $(this).show();
            }
        });

        // Update nomor urut yang tampil
        let visibleIndex = 1;
        $("#tabelAtribut tbody tr:visible").each(function () {
            $(this).find("td:first").text(visibleIndex++);
        });

        // Tampilkan pesan jika tidak ada data
        const visibleRows = $("#tabelAtribut tbody tr:visible").length;
        if (visibleRows === 0) {
            if ($("#tabelAtribut .no-data-message").length === 0) {
                $("#tabelAtribut tbody").append(`
                    <tr class="no-data-message">
                        <td colspan="3" class="text-center">
                            <div class="p-3">
                                <i class="bi bi-inbox fs-4 text-muted"></i>
                                <p class="text-muted mb-0">Tidak ada atribut yang sesuai</p>
                            </div>
                        </td>
                    </tr>
                `);
            }
        } else {
            $("#tabelAtribut .no-data-message").remove();
        }
    }

    // Fungsi untuk pencarian aplikasi
    function searchAplikasi() {
        const searchTerm = $("#searchAplikasi").val().toLowerCase();

        // Sembunyikan semua baris terlebih dahulu
        $("#tabelAplikasi tbody tr").hide();

        // Filter baris tabel aplikasi
        $("#tabelAplikasi tbody tr").each(function () {
            const namaAplikasi = $(this)
                .find("td:nth-child(2)")
                .text()
                .toLowerCase();
            const opd = $(this).find("td:nth-child(3)").text().toLowerCase();

            // Tampilkan baris jika nama aplikasi atau OPD mengandung kata yang dicari
            if (namaAplikasi.includes(searchTerm) || opd.includes(searchTerm)) {
                $(this).show();
            }
        });

        // Update nomor urut yang tampil
        let visibleIndex = 1;
        $("#tabelAplikasi tbody tr:visible").each(function () {
            $(this).find("td:first").text(visibleIndex++);
        });

        // Tampilkan pesan jika tidak ada data
        const visibleRows = $("#tabelAplikasi tbody tr:visible").length;
        if (visibleRows === 0) {
            if ($("#tabelAplikasi .no-data-message").length === 0) {
                $("#tabelAplikasi tbody").append(`
                    <tr class="no-data-message">
                        <td colspan="5" class="text-center">
                            <div class="p-3">
                                <i class="bi bi-inbox fs-4 text-muted"></i>
                                <p class="text-muted mb-0">Tidak ada aplikasi yang sesuai</p>
                            </div>
                        </td>
                    </tr>
                `);
            }
        } else {
            $("#tabelAplikasi .no-data-message").remove();
        }
    }

    // Event listeners
    $(document).ready(function () {
        // Event untuk filter atribut
        $("#atributFilter").on("change", filterAtributTable);

        // Event untuk search aplikasi dengan debounce
        let searchTimeout;
        $("#searchAplikasi").on("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchAplikasi, 300);
        });

        // Inisialisasi Select2 untuk filter atribut
        $("#atributFilter").select2({
            theme: "bootstrap-5",
            width: "100%",
            placeholder: "Filter berdasarkan atribut",
            allowClear: true,
            language: {
                noResults: function () {
                    return "Atribut tidak ditemukan";
                },
            },
        });

        // Inisialisasi filter dan search
        filterAtributTable();
    });

    // Handle form submission untuk edit atribut
    $(document).ready(function () {
        $("#editAtributForm").on("submit", function (e) {
            e.preventDefault();
            const form = $(this);
            const id = form.data("app-id");

            $.ajax({
                url: `/aplikasi/${id}/atribut`,
                method: "POST",
                data: form.serialize(),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        $("#editAtributModal").modal("hide");
                        toastr.success("Atribut berhasil diperbarui");
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        toastr.error(
                            response.message || "Gagal memperbarui atribut"
                        );
                    }
                },
                error: function (xhr) {
                    console.error("Error response:", xhr.responseText);
                    const response = xhr.responseJSON;
                    toastr.error(
                        response?.message ||
                            "Terjadi kesalahan saat memperbarui data"
                    );
                },
            });
        });
    });
});
