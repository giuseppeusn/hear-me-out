function verComentario(albumId) {
  const comentarios = window.comentariosPorAlbum?.[albumId];

  if (!comentarios || comentarios.length === 0) {
    Swal.fire('Nenhum comentário', 'Você ainda não comentou nesse álbum.', 'info');
    return;
  }

  const lista = comentarios.map((c, i) => {
    return `
      <div style="margin-bottom: 12px;">
        <p><strong>${i + 1}.</strong> ${c.mensagem}</p>
        <button class='btn btn-warning' onclick="alterarComentario(${c.id}, \`${c.mensagem}\`, ${c.autor_id}, \`${c.autor_nome}\`)">Alterar</button>
        <button class='btn btn-danger' onclick='excluirComentario(${c.id})'>Deletar</button>
      </div>
    `;
  }).join('');

  Swal.fire({
    title: 'Seus Comentários',
    html: lista,
    width: '600px',
    showConfirmButton: true,
    confirmButtonText: 'Fechar',
    didOpen: () => {
    }
  });
}
