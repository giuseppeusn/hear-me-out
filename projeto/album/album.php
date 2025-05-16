<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<script>
  function abrirFormularioAlbum() {
  Swal.fire({
    title: 'Cadastrar Álbum',
    html:
      `<input id="nome" class="swal2-input" placeholder="Nome do Álbum">` +
      `<input id="capa" class="swal2-input" placeholder="Capa do Álbum (URL ou nome do arquivo)">` +
      `<input id="data" type="date" class="swal2-input" placeholder="Data de Lançamento">`,
    confirmButtonText: 'Salvar',
    showCancelButton: true,
    focusConfirm: false,
    showCloseButton: true,
    preConfirm: () => {
      const nome = document.getElementById('nome').value;
      const capa = document.getElementById('capa').value;
      const data = document.getElementById('data').value;

      if (!nome || !capa || !data) {
        Swal.showValidationMessage('Preencha todos os campos!');
        return false;
      }

      return { nome, capa, data };
    }
  }).then((resultado) => {
    fetch('insert.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(resultado.value)
    })
    .then(response => response.text())
    .then(data => {
      Swal.fire('Sucesso!', data, 'success');
      window.location.reload();
    })
    .catch(error => {
      Swal.fire('Erro!', 'Não foi possível salvar.', 'error');
    });
  });
}
</script>


<div class="container mt-3">
  <h2>Meus álbuns</h2>
  <button type="button" class="btn btn-success" onclick="abrirFormularioAlbum()">Cadastrar Álbum</button>

  <div class="row">

    <?php
    include_once("../connect.php");
    $conexao = connect_db(); 

    $query = "
      SELECT 
        album.id AS album_id,
        album.nome AS album_nome,
        album.capa,
        album.data_lancamento,
        artista.nome AS artista_nome
      FROM album
      JOIN artista ON album.id_artista = artista.id
    ";

    $resultado = $conexao->query($query);

    if ($resultado) {
        while ($linha = $resultado->fetch_object()) {
            $btnAlterar = "<a href='index.php?page=2&id=" . $linha->album_id . "' class='btn btn-warning me-2'>Alterar</a>";
            $btnExcluir = "<a href='index.php?page=3&id=" . $linha->album_id . "' class='btn btn-danger'>Excluir</a>";
            $btnVerAlbum = "<a href='index.php?page=4&id=" . $linha->album_id . "' class='btn btn-primary'>Ver álbum</a>";


            echo "
            <div class='col-md-4 mb-4'>
              <div class='card' style='width:100%'>
                <img class='card-img-top' src='{$linha->capa}' alt='Capa do álbum'>
                <div class='card-body'>
                  <h4 class='card-title'>{$linha->album_nome}</h4>
                  <p class='card-text'><b>Artista:</b> {$linha->artista_nome}</p>
                  $btnVerAlbum
                  $btnAlterar
                  $btnExcluir
                  
                </div>
              </div>
            </div>
            ";
        }
    }
    ?>
  </div>
</div>
</body>
</html>
