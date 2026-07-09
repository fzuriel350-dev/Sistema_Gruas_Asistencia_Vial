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
            title: form.getAttribute('data-confirm-title') || '¿Confirmar acción?',
            text: form.getAttribute('data-confirm'),
            icon: form.getAttribute('data-confirm-icon') || 'question',
            showCancelButton: true,
            confirmButtonText: form.getAttribute('data-confirm-button') || 'Sí, confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: form.getAttribute('data-confirm-button') === 'Sí, eliminar' ? '#dc2626' : '#2563eb',
            reverseButtons: true,
            customClass: {
                popup: 'swal-popup-custom',
                title: 'swal-title-custom',
                confirmButton: 'swal-confirm-custom',
                cancelButton: 'swal-cancel-custom',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
});
