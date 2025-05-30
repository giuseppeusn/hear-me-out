function abrirInserirMusica(albumId) {
  Swal.fire({
    title: "Inserir música",
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
        <input id="data" type="date" class="input-field" placeholder="Data de lançamento">
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
