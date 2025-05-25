<div class='table-responsive mt-4'>
  <table class='table table-striped'>
    <thead>
      <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Duração</th>
        <th>Nota</th>
      </tr>
    </thead>
    <tbody>
      <?php while($musica = $musicas->fetch_object()): ?>
        <div id='musica-<?= $musica->musica_id ?>' 
             data-nome="<?= htmlspecialchars($musica->musica_nome) ?>"
             data-capa="<?= htmlspecialchars($musica->musica_capa) ?>"
             data-duracao="<?= htmlspecialchars($musica->musica_duracao) ?>"
             data-data="<?= $musica->musica_data ?>" 
             style='display: none;'></div>

        <tr>
          <td><img src='<?= $musica->musica_capa ?>' style='width: 50px;'></td>
          <td><a href='/hear-me-out/projeto/paginaUser/musica.php?id=<?= $musica->musica_id ?>'><?= $musica->musica_nome ?></a></td>
          <td><?= formatarDuracao($musica->musica_duracao) ?></td>
          <td>Sem avaliação</td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
