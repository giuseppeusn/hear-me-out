<script>
    document.querySelectorAll('.aprovar-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            Swal.fire({
                title: 'Aprovar este cadastro?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, aprovar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = link.href;
                }
            });
        });
    });
</script>