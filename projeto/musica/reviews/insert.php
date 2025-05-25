<?php
  include_once("../../connect.php");

  $nota = isset($_POST['nota']) ? floatval($_POST['nota']) : null;
  $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : null;
  $musica_id = isset($_POST['musica_id']) ? intval($_POST['musica_id']) : null;
  $avaliador_id = isset($_POST['avaliador_id']) ? intval($_POST['avaliador_id']) : null;
  $avaliador_tipo = isset($_POST['avaliador_tipo']) ? $_POST['avaliador_tipo'] : null;

  if ($nota === null || $mensagem === null || $musica_id === null || $avaliador_id === null || $avaliador_tipo === null) {
      die('Dados inválidos.');
  }

  $id_usuario = null;
  $id_critico = null;

  if ($avaliador_tipo === 'usuario') {
      $id_usuario = $avaliador_id;
  } elseif ($avaliador_tipo === 'critico') {
      $id_critico = $avaliador_id;
  }

  $connection = connect_db();

  $stmt = $connection->prepare("INSERT INTO avaliacao (mensagem, nota, id_usuario, id_critico) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sdii", $mensagem, $nota, $id_usuario, $id_critico);

  if ($stmt->execute()) {
      $idAvaliacao = $stmt->insert_id;

      $stmt2 = $connection->prepare("INSERT INTO avaliacao_musica (id_avaliacao, id_musica) VALUES (?, ?)");
      $stmt2->bind_param("ii", $idAvaliacao, $musica_id);

      if ($stmt2->execute()) {
          echo "Avaliação inserida com sucesso!";
      } else {
          echo "Erro ao vincular a avaliação na música: " . $stmt2->error;
      }

      $stmt2->close();
  } else {
      echo "Erro ao inserir a avaliação: " . $stmt->error;
  }

  $stmt->close();
  $connection->close();
?>
