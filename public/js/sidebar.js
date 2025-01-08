document.addEventListener("DOMContentLoaded", function () {
    const btnToggleSidebar = document.querySelector(".btn-toggle-sidebar");
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".sidebar-overlay");

    btnToggleSidebar.addEventListener("click", function () {
        sidebar.classList.toggle("show");
        overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", function () {
        sidebar.classList.remove("show");
        overlay.classList.remove("active");
    });
});