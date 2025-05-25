<script>
  function openUserReviews() {
    const avaliacoes = <?= json_encode(renderReviews($avaliacoes['avaliacoes']['usuario'] ?? [])); ?>;

    Swal.fire({
      title: 'Avaliações dos usuários',
      html: avaliacoes,
      showConfirmButton: false,
      showCancelButton: true,
      cancelButtonText: 'Fechar',
    });
  }

  function openCriticismReviews() {
    const avaliacoes = <?= json_encode(renderReviews($avaliacoes['avaliacoes']['critico'] ?? [])); ?>;

    Swal.fire({
      title: 'Avaliações dos críticos',
      html: avaliacoes,
      showConfirmButton: false,
      showCancelButton: true,
      cancelButtonText: 'Fechar',
    })
  }

  function openFormReview() {
    Swal.fire({
      title: 'Adicionar avaliação',
      html: `
        <form id="avaliacaoForm">
          <div class="review-area">
            <label for="nota">Nota (0 a 5)</label>
            <input type="number" class="form-control" id="nota" name="nota" min="0" max="5" step="0.1" required>
          </div>
          <div class="review-area">
            <label for="mensagem">Mensagem</label>
            <textarea class="form-control" id="mensagem" name="mensagem" maxlength="500" required></textarea>
          </div>
          <input type="hidden" name="album_id" value="<?= $album->album_id ?>">
          <input type="hidden" name="avaliador_id" value="<?= $_SESSION['id'] ?>">
          <input type="hidden" name="avaliador_tipo" value="<?= $tipoAvaliador ?>">
        </form>
      `,
      confirmButtonText: 'Adicionar',
      showCancelButton: true,
      cancelButtonText: 'Fechar',
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        insertReview();
      }
    });
  }

  function openUpdateFormReview() {
    const avaliacoes = <?= json_encode($avaliacoes); ?>;

    console.log('avaliacoes:', avaliacoes);

    Swal.fire({
      title: 'Alterar avaliação',
      html: `
        <form id="avaliacaoForm">
          <div class="review-area">
            <label for="nota">Nota (0 a 5)</label>
            <input type="number" class="form-control" id="nota" name="nota" min="0" max="5" step="0.1" required value="${avaliacoes.minhaAvaliacao.nota}">
          </div>
          <div class="review-area">
            <label for="mensagem">Mensagem</label>
            <textarea class="form-control" id="mensagem" name="mensagem" maxlength="500" required>${avaliacoes.minhaAvaliacao.mensagem}</textarea>
          </div>
          <input type="hidden" name="avaliacao_id" value="${avaliacoes.minhaAvaliacao.id_avaliacao}">
          <input type="hidden" name="album_id" value="<?= $album->album_id ?>">
          <button type="button" class="review-delete" onclick="deleteReview(${avaliacoes.minhaAvaliacao.id_avaliacao})">
            Excluir avaliação
          </button>
        </form>
      `,
      confirmButtonText: 'Atualizar',
      showCancelButton: true,
      cancelButtonText: 'Fechar',
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        updateReview();
      }
    });
  }

  function deleteReview(id) {
    Swal.fire({
      title: 'Excluir avaliação',
      text: 'Você tem certeza que deseja excluir esta avaliação?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sim, excluir',
      cancelButtonText: 'Cancelar'
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        fetch(`../album/reviews/delete.php?id=${id}`, {
          method: 'DELETE'
        })
        .then(response => response.text())
        .then(data => {
          Swal.fire({
            title: 'Avaliação excluída!',
            icon: 'success',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Fechar'
          }).then(() => {
            location.reload();
          });
        })
        .catch(error => {
          Swal.fire({
            title: 'Erro ao excluir avaliação',
            text: error,
            icon: 'error',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Fechar',
          });
        });
      }
    });
  }

  function updateReview() {
    const form = document.getElementById('avaliacaoForm');
    const formData = new FormData(form);

    fetch('../album/reviews/update.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
          title: 'Avaliação atualizada!',
          icon: 'success',
          showConfirmButton: false,
          showCancelButton: true,
          cancelButtonText: 'Fechar'
        }).then(() => {
          location.reload();
        })
    }).catch(error => {
      Swal.fire({
        title: 'Erro ao atualizar avaliação',
        text: error,
        icon: 'error',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Fechar',
      });
    })
  }

  function insertReview() {
    const form = document.getElementById('avaliacaoForm');
    const formData = new FormData(form);

    fetch('../album/reviews/insert.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
          title: 'Avaliação enviada!',
          icon: 'success',
          showConfirmButton: false,
          showCancelButton: true,
          cancelButtonText: 'Fechar'
        }).then(() => {
          location.reload();
        })
    }).catch(error => {
      Swal.fire({
        title: 'Erro ao enviar avaliação',
        text: error,
        icon: 'error',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Fechar',
      });
    })
  }
</script>