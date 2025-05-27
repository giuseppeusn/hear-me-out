function abrirAlterarMusica(botao, musicaId) {

  const nome = botao.getAttribute('data-nome');
  const capa = botao.getAttribute('data-capa');
  const duracao = botao.getAttribute('data-duracao');
  const data = botao.getAttribute('data-data');

  Swal.fire({
    title: 'Alterar música',
    html:
      `<input id="nome" class="swal2-input" placeholder="Nome do Álbum" value="${nome}">` +
      `<input id="capa" class="swal2-input" placeholder="Capa do Álbum" value="${capa}">` +
      `<input id="duracao" class="swal2-input" type="number" placeholder="Duração da música" value="${duracao}">` +
      `<input id="data" type="date" class="swal2-input" value="${data}">`,
    confirmButtonText: 'Salvar',
    showCancelButton: true,
    showCloseButton: true,
    focusConfirm: false,
    preConfirm: () => {
      const nome = document.getElementById('nome').value.trim();
      const capa = document.getElementById('capa').value.trim();
      const duracao = parseInt(document.getElementById('duracao').value.trim(), 10);
      const data = document.getElementById('data').value;

      if (!nome) {
        Swal.showValidationMessage('O nome da música é obrigatório.');
        return false;
      }

      if (isNaN(duracao) || duracao <= 0) {
        Swal.showValidationMessage('A duração da música deve ser um número maior que zero.');
        return false;
      }

      if (!capa) {
        Swal.showValidationMessage('A capa é obrigatória.');
        return false;
      }

      if (!data) {
        Swal.showValidationMessage('A data de lançamento é obrigatória.');
        return false;
      }

      const capaValida = /\.(png|jpg)$/i.test(capa);
      if (!capaValida) {
        Swal.showValidationMessage('A imagem da capa deve ser um arquivo .png ou .jpg');
        return false;
      }

      return { nome, duracao, capa, data };
    }
  }).then((resultado) => {
    if (resultado.isConfirmed) {
      fetch('/hear-me-out/projeto/artista/meus-albuns/musicas/update.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ...resultado.value, id: musicaId })
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
