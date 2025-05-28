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
          <p style="color:gray" class="mb-1">Campo obrigatório *</p>
          <div class="review-area">
            <label for="nota">* Nota (0 a 5)</label>
            <input type="number" class="form-control" id="nota" name="nota" min="0" max="5" step="0.1" required>
          </div>
          <div class="review-area">
            <label for="mensagem">* Mensagem (máx. 500 letras)</label>
            <textarea class="form-control" id="mensagem" name="mensagem" maxlength="500" placeholder="Digite uma mensagem para a sua avaliação..." required></textarea>
          </div>
          <input type="hidden" name="musica_id" value="<?= $musica->musica_id ?>">
          <input type="hidden" name="avaliador_id" value="<?= isset($_SESSION['id']) ? $_SESSION['id'] : null ?>">
          <input type="hidden" name="avaliador_tipo" value="<?= $tipoAvaliador ?>">
        </form>
      `,
      confirmButtonText: 'Adicionar',
      showCloseButton: true,
      preConfirm: () => {
        const form = document.getElementById('avaliacaoForm');
        if (!form.checkValidity()) {
          Swal.showValidationMessage('Preencha todos os campos corretamente');
          return false;
        }
        return true;
      },
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
            <label for="mensagem">Mensagem (máx. 500 letras)</label>
            <textarea class="form-control" id="mensagem" name="mensagem" maxlength="500" required>${avaliacoes.minhaAvaliacao.mensagem}</textarea>
          </div>
          <input type="hidden" name="avaliacao_id" value="${avaliacoes.minhaAvaliacao.id_avaliacao}">
          <input type="hidden" name="musica_id" value="<?= $musicas->musica_id ?>">
          <div class="review-actions">
            <button type="button" class="review-update" onclick="validateUpdate()">
              Atualizar avaliação
            </button>
            <button type="button" class="review-delete" onclick="deleteReview(${avaliacoes.minhaAvaliacao.id_avaliacao})">
              Excluir avaliação
            </button>
          </div>
        </form>
      `,
      showConfirmButton: false,
      showCloseButton: true,
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        updateReview();
      }
    });
  }

  function validateUpdate() {
    const form = document.getElementById('avaliacaoForm');
    if (!form.checkValidity()) {
      console.log('Form inválido');
      Swal.showValidationMessage('Preencha todos os campos corretamente');
      return;
    }

    updateReview();
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
        fetch(`../musica/reviews/delete.php?id=${id}`, {
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

    fetch('../musica/reviews/update.php', {
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

    fetch('../musica/reviews/insert.php', {
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