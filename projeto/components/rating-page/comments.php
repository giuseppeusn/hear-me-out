<div class='section mt-4'>
  <h4 class='section-title'>Comentários</h4>
  <form id='comentarioForm'>
    <div class='comment-area'>
      <textarea class='form-control' name='comentario_mensagem' id='comentario_mensagem' maxlength='250' placeholder='Adicione um comentário...'></textarea>
      <button id='enviarComent' type='submit' class='comment-btn'>
        <img src='../assets/svg/send.svg' alt='Enviar'>
      </button>
    </div>
    <?php
      if (isset($album_id)) {
        echo "<input type='hidden' name='album_id' value='{$album_id}'>";
      } else {
        echo "<input type='hidden' name='musica_id' value='{$musica_id}'>";
      }
    ?>
  </form>

  <?php if ($comentarios->num_rows > 0): ?>
    <?php while ($comentario = $comentarios->fetch_object()): ?>
      <div class='comment'>
        <div class='comment-img'>
          <img src='../assets/svg/user.svg' alt='User'>
          <div class='comment-text'>
            <p class='comment-name'><?= htmlspecialchars($comentario->comentario_nome) ?></p>
            <p class='comment-msg'><?= htmlspecialchars($comentario->comentario_mensagem) ?></p>
          </div>
        </div>

        <?php if (isset($_SESSION['id']) && $comentario->comentario_idAutor == $_SESSION['id']): ?>
          <div class='comment-edit-wrapper'>
            <button class='comment-edit' onclick='alterarComentario(<?= $comentario->comentario_id ?>, <?= json_encode($comentario->comentario_mensagem) ?>)' type='button'>
              <img src='../assets/svg/edit.svg' alt='Editar'>
            </button>
            <button class='comment-edit' onclick='excluirComentario(<?= $comentario->comentario_id ?>)' type='button'>
              <img src='../assets/svg/trash.svg' alt='Excluir'>
            </button>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style='text-align: center;'>Seja o primeiro a comentar!</p>
  <?php endif; ?>

  <div id='mensagem'></div>
</div>
