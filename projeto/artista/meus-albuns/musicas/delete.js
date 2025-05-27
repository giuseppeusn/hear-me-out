function deleteMusica(musicaId) {
  Swal.fire({
    title: 'Deseja realmente excluir esta música?',
    text: 'Essa ação não poderá ser desfeita.',
    icon: 'warning',
    confirmButtonText: 'Sim, excluir',
    cancelButtonText: 'Cancelar',
    showCancelButton: true,
    showCloseButton: true,
    focusConfirm: false
  }).then((resultado) => {
    if (resultado.isConfirmed) {
      fetch('/hear-me-out/projeto/artista/meus-albuns/musicas/delete.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: musicaId })
      })
      .then(response => response.text())
      .then(data => {
        Swal.fire('Excluído!', data, 'success').then(() => {
          window.location.reload();
        });
      })
      .catch(error => {
        Swal.fire('Erro!', 'Não foi possível excluir.', 'error');
      });
    }
  });
}
