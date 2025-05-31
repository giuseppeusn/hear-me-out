<script>
  function abrirFormularioAlbum() {
    Swal.fire({
      title: 'Novo álbum',
      html:`<form>
        <p style="color:gray" class="mb-1">Campo obrigatório *</p>
        <div class="form-area">
          <label for="nome" class="input-label">* Nome</label>
          <input id="nome" class="input-field" placeholder="Nome do álbum">
        </div>
        <div class="form-area">
          <label for="capa" class="input-label">* Capa</label>
          <input id="capa" class="input-field" placeholder="Capa do álbum (URL)">
        </div>
        <div class="form-area">
          <label for="data" class="input-label">* Lançamento</label>
          <input id="data" type="date" class="input-field" placeholder="Data de lançamento" value="${new Date().toISOString().split('T')[0]}">
        </div>
      </form>`,
      confirmButtonText: 'Adicionar',
      showCloseButton: true,
      focusConfirm: false,
      preConfirm: () => {
        const nome = document.getElementById('nome').value.trim();
        const capa = document.getElementById('capa').value.trim();
        const data = document.getElementById('data').value;
        if (!nome) {
          Swal.showValidationMessage('O nome do álbum é obrigatório.');
          return false;
        }
        if (!capa) {
          Swal.showValidationMessage('O link ou nome da capa é obrigatório.');
          return false;
        }
        if (!data) {
          Swal.showValidationMessage('A data de lançamento é obrigatória.');
          return false;
        }
        const capaValida = /\.(png|jpg)$/i.test(capa);
        if (!capaValida) {
          Swal.showValidationMessage('A imagem da capa deve ser um link .png ou .jpg');
          return false;
        }

        return { nome, capa, data };
      }
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        fetch('insert.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(resultado.value)
        })
        .then(response => response.text())
        .then(data => {
          Swal.fire('Sucesso!', data, 'success').then(() => {
            window.location.reload();
          });
        })
        .catch(error => {
          Swal.fire('Erro!', 'Não foi possível salvar.', 'error');
        });
      }
    });
  }

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

  function abrirAlterarAlbum(albumId) {
    const container = document.getElementById(`album-${albumId}`);
    const nome = container.getAttribute('data-nome');
    const capa = container.getAttribute('data-capa');
    const data = container.getAttribute('data-data');

    Swal.fire({
      title: 'Alterar álbum',
      html:`<form>
        <p style="color:gray" class="mb-1">Campo obrigatório *</p>
        <div class="form-area">
          <label for="nome" class="input-label">* Nome</label>
          <input id="nome" class="input-field" placeholder="Nome do álbum" value="${nome}">
        </div>
        <div class="form-area">
          <label for="capa" class="input-label">* Capa</label>
          <input id="capa" class="input-field" placeholder="Capa do álbum (URL)" value="${capa}">
        </div>
        <div class="form-area">
          <label for="data" class="input-label">* Lançamento</label>
          <input id="data" type="date" class="input-field" value="${data}">
        </div>
      </form>`,
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