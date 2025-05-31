<script>
  function abrirInserirMusica(albumId) {
    Swal.fire({
      title: "Nova música",
      html: `<form>
        <p style="color:gray" class="mb-1">Campo obrigatório *</p>
        <div class="form-area">
          <label for="nome" class="input-label">* Nome</label>
          <input id="nome" class="input-field" placeholder="Nome da música">
        </div>
        <div class="form-area">
          <label for="capa" class="input-label">* Capa</label>
          <input id="capa" class="input-field" placeholder="Capa da música (URL)">
        </div>
        <div class="form-area">
          <label for="duracao" class="input-label">* Duração</label>
          <input id="duracao" class="input-field" type="number" placeholder="Duração da música (em segundos)">
        </div>
        <div class="form-area">
          <label for="data" class="input-label">* Lançamento</label>
          <input id="data" type="date" class="input-field" placeholder="Data de lançamento" value="${new Date().toISOString().split('T')[0]}">
        </div>
      </form>`,
      confirmButtonText: "Adicionar",
      showCloseButton: true,
      focusConfirm: false,

      preConfirm: () => {
        const nome = document.getElementById("nome").value.trim();
        const duracao = parseInt(
          document.getElementById("duracao").value.trim(),
          10
        );
        const capa = document.getElementById("capa").value.trim();
        const data = document.getElementById("data").value;

        if (!nome) {
          Swal.showValidationMessage("O nome da música é obrigatório.");
          return false;
        }

        if (isNaN(duracao) || duracao <= 0) {
          Swal.showValidationMessage(
            "A duração da música deve ser um número maior que zero."
          );
          return false;
        }

        if (!capa) {
          Swal.showValidationMessage("A capa é obrigatória.");
          return false;
        }

        if (!data) {
          Swal.showValidationMessage("A data de lançamento é obrigatória.");
          return false;
        }

        const capaValida = /\.(png|jpg)$/i.test(capa);
        if (!capaValida) {
          Swal.showValidationMessage(
            "A imagem da capa deve ser um arquivo .png ou .jpg"
          );
          return false;
        }

        return { nome, duracao, capa, data };
      },
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        const { nome, duracao, capa, data } = resultado.value;

        fetch("/hear-me-out/projeto/artista/meus-albuns/musicas/insert.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ nome, duracao, capa, data, albumId }),
        })
          .then((response) => response.text())
          .then((data) => {
            Swal.fire("Sucesso!", data, "success").then(() => {
              window.location.reload();
            });
          })
          .catch((error) => {
            Swal.fire("Erro!", "Não foi possível salvar.", "error");
          });
      }
    });
  }

  function abrirAlterarMusica(botao, musicaId) {
    const nome = botao.getAttribute("data-nome");
    const capa = botao.getAttribute("data-capa");
    const duracao = botao.getAttribute("data-duracao");
    const data = botao.getAttribute("data-data");

    Swal.fire({
      title: "Alterar música",
      html: `<form>
        <p style="color:gray" class="mb-1">Campo obrigatório *</p>
        <div class="form-area">
          <label for="nome" class="input-label">* Nome</label>
          <input id="nome" class="input-field" placeholder="Nome do Álbum" value="${nome}">
        </div>
        <div class="form-area">
          <label for="capa" class="input-label">* Capa (URL)</label>
          <input id="capa" class="input-field" placeholder="Capa do Álbum" value="${capa}">
        </div>
        <div class="form-area">
          <label for="duracao" class="input-label">* Duração (em segundos)</label>
          <input id="duracao" class="input-field" type="number" placeholder="Duração da música" value="${duracao}">
        </div>
        <div class="form-area">
          <label for="data" class="input-label">* Lançamento</label>
          <input id="data" type="date" class="input-field" value="${data}" value="${new Date().toISOString().split('T')[0]}">
        </div>
      </form>`,
      confirmButtonText: "Salvar",
      showCloseButton: true,
      focusConfirm: false,
      preConfirm: () => {
        const nome = document.getElementById("nome").value.trim();
        const capa = document.getElementById("capa").value.trim();
        const duracao = parseInt(
          document.getElementById("duracao").value.trim(),
          10
        );
        const data = document.getElementById("data").value;

        if (!nome) {
          Swal.showValidationMessage("O nome da música é obrigatório.");
          return false;
        }

        if (isNaN(duracao) || duracao <= 0) {
          Swal.showValidationMessage(
            "A duração da música deve ser um número maior que zero."
          );
          return false;
        }

        if (!capa) {
          Swal.showValidationMessage("A capa é obrigatória.");
          return false;
        }

        if (!data) {
          Swal.showValidationMessage("A data de lançamento é obrigatória.");
          return false;
        }

        const capaValida = /\.(png|jpg)$/i.test(capa);
        if (!capaValida) {
          Swal.showValidationMessage(
            "A imagem da capa deve ser um arquivo .png ou .jpg"
          );
          return false;
        }

        return { nome, duracao, capa, data };
      },
    }).then((resultado) => {
      if (resultado.isConfirmed) {
        fetch("/hear-me-out/projeto/artista/meus-albuns/musicas/update.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ ...resultado.value, id: musicaId }),
        })
          .then((response) => response.text())
          .then((data) => {
            Swal.fire("Alterado!", data, "success").then(() => {
              window.location.reload();
            });
          })
          .catch((error) => {
            Swal.fire("Erro!", "Não foi possível alterar.", "error");
          });
      }
    });
  }

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
</script>