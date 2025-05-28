<script>
  function abrirAlterarAlbum(albumId) {
  const container = document.getElementById(`album-${albumId}`);
  const nome = container.getAttribute('data-nome');
  const capa = container.getAttribute('data-capa');
  const data = container.getAttribute('data-data');

  Swal.fire({
    title: 'Alterar Álbum',
    html:
      `<input id="nome" class="swal2-input" placeholder="Nome do Álbum" value="${nome}">` +
      `<input id="capa" class="swal2-input" placeholder="Capa do Álbum" value="${capa}">` +
      `<input id="data" type="date" class="swal2-input" value="${data}">`,
    confirmButtonText: 'Salvar',
    showCancelButton: true,
    showCloseButton: true,
    focusConfirm: false,
    preConfirm: () => {
      const nome = document.getElementById('nome').value.trim();
      const capa = document.getElementById('capa').value.trim();
      const data = document.getElementById('data').value;

      if (!nome) return Swal.showValidationMessage('O nome do álbum é obrigatório.');
      if (!capa) return Swal.showValidationMessage('A capa é obrigatória.');
      if (!data) return Swal.showValidationMessage('A data de lançamento é obrigatória.');
      if (!/\.(png|jpg)$/i.test(capa)) return Swal.showValidationMessage('A imagem da capa deve ser .png ou .jpg');

      return { nome, capa, data, id: albumId };
    }
  }).then((resultado) => {
    if (resultado.isConfirmed) {
      fetch('update.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(resultado.value)
      })
      .then(response => response.text())
      .then(data => {
        Swal.fire('Alterado!', data, 'success').then(() => {
          window.location.reload();
        });
      })
      .catch(error => {
        Swal.fire('Erro!', 'Não foi possível alterar.', 'error');
      });
    }
  });
}

</script>