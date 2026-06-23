import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

document.addEventListener('submit', function (e) {
    const form = e.target;
    if (form.hasAttribute('data-confirm')) {
        e.preventDefault();
        Swal.fire({
            title: form.getAttribute('data-confirm'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
});
