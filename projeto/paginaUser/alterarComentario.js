async function alterarComentario(comentarioId, mensagem, autorId, autorNome) {
console.log("Mensagem recebida:", mensagem);
  const usuarioId = sessionStorage.getItem('id_usuario');
  const usuarioNome = sessionStorage.getItem('nome_usuario');

  if (String(usuarioId) !== String(autorId) || usuarioNome !== autorNome) {
    await Swal.fire('Acesso negado', 'Você só pode editar seu próprio comentário.', 'error');
    return;
  }

  const { value: novaMensagem, isConfirmed } = await Swal.fire({
    title: 'Alterar Comentário',
    input: 'textarea',
    inputValue: mensagem || '',
    inputPlaceholder: 'Comentário',
    showCancelButton: true,
    showCloseButton: true,
    confirmButtonText: 'Salvar',
    preConfirm: (texto) => {
      const textoTeste = texto ? texto.trim() : '';
      if (!textoTeste) {
        Swal.showValidationMessage('O comentário não pode estar vazio.');
        return;
      }
      return textoTeste;

}

  });
  console.log("Confirmado:", isConfirmed);
  console.log("Nova mensagem retornada:", novaMensagem);
  if (!isConfirmed || !novaMensagem) return;

  try {
    const resposta = await fetch('updateComentario.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ mensagem: novaMensagem, id: comentarioId })
    });

    const dadosResposta = await resposta.json().catch(() => {
        throw new Error("Resposta do servidor não é JSON válido.");
    });

    if (!resposta.ok) {
        throw new Error(dadosResposta.erro || "Erro ao atualizar comentário.");
    }

    await Swal.fire('Sucesso!', dadosResposta.sucesso || "Comentário atualizado.", 'success');
    window.location.reload();

} catch (error) {
    console.error('Erro completo:', error);
    Swal.fire('Erro!', error.message || "Erro desconhecido.", 'error');
}
}