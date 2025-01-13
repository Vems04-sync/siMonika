document.addEventListener("DOMContentLoaded", function () {
    const btnToggleSidebar = document.querySelector(".btn-toggle-sidebar");
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".sidebar-overlay");
    const mainContent = document.querySelector(".main-content");

    // Fungsi untuk toggle sidebar
    function toggleSidebar() {
        sidebar.classList.toggle("show");
        overlay.classList.toggle("active");

        // Hapus pengaturan margin untuk main content
        // agar konten tidak bergeser saat sidebar muncul
        mainContent.style.marginLeft = "0";
    }

    // Fungsi untuk mengecek lebar layar
    function checkScreenSize() {
        if (window.innerWidth <= 1030) {
            sidebar.classList.remove("show");
            mainContent.style.marginLeft = "0";
            overlay.classList.remove("active");
            if (btnToggleSidebar) {
                btnToggleSidebar.style.display = "block";
            }
        } else {
            sidebar.classList.add("show");
            mainContent.style.marginLeft = "250px"; // Hanya berikan margin di layar besar
            overlay.classList.remove("active");
            if (btnToggleSidebar) {
                btnToggleSidebar.style.display = "none";
            }
        }
    }

    // Jalankan saat halaman dimuat
    checkScreenSize();

    // Jalankan saat ukuran window berubah
    window.addEventListener("resize", checkScreenSize);

    if (btnToggleSidebar) {
        btnToggleSidebar.addEventListener("click", toggleSidebar);
    }

    if (overlay) {
        overlay.addEventListener("click", toggleSidebar);
    }
});
