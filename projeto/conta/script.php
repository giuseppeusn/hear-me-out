<script>
    document.getElementById('btnEditarPerfil').addEventListener('click', function() {
        const form = document.getElementById('formPerfil');
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            if (input.name !== 'id' && input.name !== 'cpf') {
                input.disabled = false;
            }
        });
        document.getElementById('btnEditarPerfil').style.display = 'none';
        document.getElementById('btnSalvarPerfil').style.display = 'inline-block';
        document.getElementById('btnCancelarPerfil').style.display = 'inline-block';
        document.getElementById('btnExcluirConta').style.display = 'none'; 
    });
    
    document.getElementById('formPerfil').addEventListener('submit', function(event) {
        event.preventDefault();
    
        const form = event.target;
        const formData = new FormData(form);
        const jsonData = {};
        formData.forEach(function(value, key){
            jsonData[key] = value;
        });
    
        const userType = '<?= $userType ?>';
        let url = '';
    
        if (userType === 'usuario') {
            url = 'update.php';
        } else if (userType === 'critico') {
            url = 'update_critico.php';
        } else if (userType === 'artista') {
            url = 'update_artista.php';
        }

        console.log('jsonData:', jsonData);
    
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire(
                    'Sucesso!',
                    data.message,
                    'success'
                ).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire(
                    'Erro!',
                    data.message || 'Ocorreu um erro ao atualizar o perfil.',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire(
                'Erro!',
                'Ocorreu um erro de rede ou no servidor.',
                'error'
            );
        });
    });

    document.getElementById('btnCancelarPerfil').addEventListener('click', function() {
        const form = document.getElementById('formPerfil');
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            input.disabled = true;
        });
        document.getElementById('btnEditarPerfil').style.display = 'inline-block';
        document.getElementById('btnSalvarPerfil').style.display = 'none';
        document.getElementById('btnCancelarPerfil').style.display = 'none';
        document.getElementById('btnExcluirConta').style.display = 'inline-block'; 
    });
    
    document.getElementById('btnExcluirConta').addEventListener('click', function() {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const userId = <?= htmlspecialchars($userData['id']) ?>;
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Excluído!',
                            'Sua conta foi excluída com sucesso.',
                            'success'
                        ).then(() => {
                            window.location.href = '../index.php';
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            data.message || 'Ocorreu um erro ao excluir sua conta.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro de rede ou no servidor.',
                        'error'
                    );
                });
            }
        });
    });
</script>