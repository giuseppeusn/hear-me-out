function deleteMusica(musicaId) {
  const container = document.getElementById(`musica-${musicaId}`);

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
      fetch('/hear-me-out/projeto/musica/delete.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: musicaId })
      })
      .then(response => response.text())
      .then(data => {
        Swal.fire('Excluído!', data, 'success').then(() => {
          if (container) {
            container.remove();}
            window.location.reload();
          }
        );
      });
    }
  });
}
