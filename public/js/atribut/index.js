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
        success: function (response) {
            const atributs = response.atribut_tambahans;
            let html = "";

            if (atributs && atributs.length > 0) {
                atributs.forEach((atribut) => {
                    // Tambahkan atribut type sesuai tipe data
                    const inputType = getInputType(atribut.tipe_data);
                    html += `
                        <div class="mb-3">
                            <label class="form-label">${
                                atribut.nama_atribut
                            }</label>
                            <input type="${inputType}"
                                   class="form-control"
                                   name="nilai_atribut[${atribut.id_atribut}]"
                                   value="${atribut.pivot?.nilai_atribut || ""}"
                                   ${getInputAttributes(atribut.tipe_data)}
                                   placeholder="Masukkan nilai untuk ${
                                       atribut.nama_atribut
                                   }">
                            <div class="invalid-feedback"></div>
                        </div>
                    `;
                });
            } else {
                html =
                    '<div class="alert alert-info">Belum ada atribut yang ditambahkan</div>';
            }

            $("#atributFields").html(html);
            $("#editAtributForm").data("app-id", id);
            $("#editAtributModal").modal("show");
        },
        error: function () {
            toastr.error("Gagal memuat data atribut");
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
                    localStorage.setItem(
                        "flash_message",
                        isEdit
                            ? "Data atribut berhasil diperbarui!"
                            : "Data atribut berhasil ditambahkan!"
                    );
                    window.location.reload();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '<ul class="m-0">';
                        Object.values(errors).forEach((error) => {
                            errorMessage += `<li>${error[0]}</li>`;
                        });
                        errorMessage += "</ul>";
                        toastr.error(errorMessage, "Validasi Gagal");
                    } else {
                        toastr.error(
                            "Terjadi kesalahan saat menyimpan data",
                            "Error"
                        );
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

    // Handle submit form tambah atribut
    $("#tambahAtributForm").on("submit", function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "/atribut",
            method: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#tambahAtributModal").modal("hide");
                    toastr.success(response.message);
                    // Reset form
                    $("#tambahAtributForm")[0].reset();
                    // Refresh halaman setelah delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = "";
                    for (let field in errors) {
                        errorMessage += `${errors[field]}\n`;
                    }
                    toastr.error(errorMessage);
                } else {
                    toastr.error("Gagal menambahkan atribut");
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

    // Handle submit form edit atribut
    $("#editAtributForm").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        const formData = new FormData(this);
        const appId = $(this).data("app-id");

        // Tambahkan CSRF token
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

        $.ajax({
            url: `/aplikasi/${appId}/update-atribut`,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $("#editAtributModal").modal("hide");
                    toastr.success("Nilai atribut berhasil diperbarui");
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toastr.error(
                        response.message || "Gagal memperbarui nilai atribut"
                    );
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const error = xhr.responseJSON;
                    toastr.error(error.message);
                    if (error.field) {
                        const input = form.find(`input[name="${error.field}"]`);
                        input.addClass("is-invalid");
                        input.siblings(".invalid-feedback").text(error.message);
                    }
                } else {
                    toastr.error("Terjadi kesalahan saat menyimpan data");
                }
            },
        });
    });
});
