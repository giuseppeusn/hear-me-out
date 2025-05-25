  function abrirFormularioAlbum() {
    Swal.fire({
      title: 'Cadastrar Álbum',
      html:
        `<input id="nome" class="swal2-input" placeholder="Nome do Álbum">` +
        `<input id="capa" class="swal2-input" placeholder="Capa do Álbum (URL ou nome do arquivo)">` +
        `<input id="data" type="date" class="swal2-input" placeholder="Data de Lançamento">`,
      confirmButtonText: 'Salvar',
      showCancelButton: true,
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
          Swal.showValidationMessage('A imagem da capa deve ser um arquivo .png ou .jpg');
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