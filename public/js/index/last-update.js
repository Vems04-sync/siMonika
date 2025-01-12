function updateLastUpdateTime() {
    fetch('/last-update')
        .then(response => response.json())
        .then(data => {
            document.getElementById('lastUpdate').textContent = 'Update Terakhir: ' + data.formatted;
        })
        .catch(error => console.error('Error:', error));
}

// Update setiap 1 menit
setInterval(updateLastUpdateTime, 60000);

// Update saat halaman dimuat
document.addEventListener('DOMContentLoaded', updateLastUpdateTime); 