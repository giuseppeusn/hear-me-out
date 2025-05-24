if (typeof Swal === 'undefined') {
    console.error('SweetAlert2 não está carregado!');
}


window.addAvaliacao = function(itemId) {
    console.log('Função addAvaliacao chamada com ID:', itemId);
    
    const tipoItem = window.location.pathname.includes('album.php') ? 'album' : 'musica';
    
    Swal.fire({
        title: 'Avaliar',
        html: `<input type="number" id="nota-avaliacao" class="form-control" 
                     min="1" max="10" step="0.1" placeholder="Nota (1 a 10)" required>`,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
            const input = document.getElementById('nota-avaliacao');
            const nota = parseFloat(input.value);
            
            if (!nota || nota < 1 || nota > 10) {
                Swal.showValidationMessage('Por favor, insira uma nota entre 1 e 10');
                return false;
            }
            return { nota };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Enviando avaliação:', {itemId, tipoItem, nota: result.value.nota});
            enviarAvaliacao(itemId, tipoItem, result.value.nota);
        }
    });
}

function enviarAvaliacao(itemId, tipoItem, nota) {
    const formData = new FormData();
    formData.append('item_id', itemId);
    formData.append('tipo_item', tipoItem);
    formData.append('nota', nota);

    fetch('avaliar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na rede');
        }
        return response.json();
    })
    .then(data => {
        console.log('Resposta do servidor:', data);
        if (data.success) {
            Swal.fire('Sucesso!', 'Sua avaliação foi registrada.', 'success');
            carregarMedias(itemId, tipoItem);
        } else {
            Swal.fire('Erro!', data.message || 'Ocorreu um erro ao avaliar', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        Swal.fire('Erro!', 'Falha na comunicação com o servidor', 'error');
    });
}
