<script>
  async function alterarComentario(comentarioId, mensagem) {
  const { value: novaMensagem, isConfirmed } = await Swal.fire({
    title: "Alterar comentário",
    html: `<form>
      <p style="color:gray" class="mb-1">Campo obrigatório *</p>
      <div class="form-area">
        <label for="comentario" class="input-label">* Comentário</label>
        <textarea class="textarea-field" id="comentario" placeholder="Escreva seu comentário..." required>${mensagem}</textarea>
      </div>
    </form>`,
    showCloseButton: true,
    confirmButtonText: "Salvar",
    preConfirm: () => {
      const texto = document.getElementById("comentario").value;

      const validateText = texto ? texto.trim() : "";
      if (!validateText) {
        Swal.showValidationMessage("O comentário não pode estar vazio.");
        return;
      }
      return validateText;
    },
  });

  if (!isConfirmed || !novaMensagem) return;

  try {
    const resposta = await fetch("comments/update.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ mensagem: novaMensagem, id: comentarioId }),
    });

    const dadosResposta = await resposta.json().catch((e) => {
      throw new Error("Resposta do servidor não é JSON válido.");
    });

    if (!resposta.ok) {
      throw new Error(dadosResposta.erro || "Erro ao atualizar comentário.");
    }

    await Swal.fire(
      "Sucesso!",
      dadosResposta.sucesso || "Comentário atualizado.",
      "success"
    );
    window.location.reload();
  } catch (error) {
    Swal.fire("Erro!", error.message || "Erro desconhecido.", "error");
  }
}

function excluirComentario(musicaId) {
  const container = document.getElementById(`musica-${musicaId}`);

  Swal.fire({
    title: "Deseja realmente excluir o comentário?",
    text: "Essa ação não poderá ser desfeita.",
    icon: "warning",
    confirmButtonText: "Sim, excluir",
    cancelButtonText: "Cancelar",
    showCancelButton: true,
    showCloseButton: true,
    focusConfirm: false,
  }).then((resultado) => {
    if (resultado.isConfirmed) {
      fetch("comments/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: musicaId }),
      })
        .then((response) => response.text())
        .then((data) => {
          Swal.fire("Excluído!", data, "success").then(() => {
            if (container) {
              container.remove();
            }
            window.location.reload();
          });
        });
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("comentarioForm");

  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("comments/insert.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      if (data.includes("erro:usuario_nao_logado")) {
        Swal.fire({
          icon: "warning",
          title: "Você precisa estar logado para comentar!",
          showCancelButton: true,
          confirmButtonText: "Fazer login",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../login.php";
          }
        });
        return;
      }

      Swal.fire({
        icon: data.includes("sucesso") ? "success" : "error",
        title: data,
        timer: 2500,
        showConfirmButton: true
      }).then(() => {
        form.reset();
        window.location.reload();
      });
    })
    .catch(err => {
      Swal.fire({
        icon: "error",
        title: "Erro ao enviar comentário.",
        text: err.toString()
      });
    });
  });
});

</script>