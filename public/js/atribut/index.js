document.addEventListener('DOMContentLoaded', function() {
    // Handle Edit Button Click
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/atribut/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('editAtributForm');
                    form.action = `/atribut/${id}`;
                    form.querySelector('[name="id_aplikasi"]').value = data.id_aplikasi;
                    form.querySelector('[name="nama_atribut"]').value = data.nama_atribut;
                    form.querySelector('[name="nilai_atribut"]').value = data.nilai_atribut || '';
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Auto close alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    });
}); 