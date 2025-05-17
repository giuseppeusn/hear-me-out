function deleteAlbum(albumId) {
  const container = document.getElementById(`album-${albumId}`);

  Swal.fire({
    title: 'Deseja realmente excluir este álbum?',
    text: 'Essa ação não poderá ser desfeita.',
    icon: 'warning',
    confirmButtonText: 'Sim, excluir',
    cancelButtonText: 'Cancelar',
    showCancelButton: true,
    showCloseButton: true,
    focusConfirm: false
  }).then((resultado) => {
    if (resultado.isConfirmed) {
      fetch('delete.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: albumId })
      })
      .then(response => response.text())
      .then(data => {
        Swal.fire('Excluído!', data, 'success').then(() => {
          if (container) {
            container.remove();
            window.location.reload();
          }
        });
      })
    }
  });
}
