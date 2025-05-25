<?php
  include_once("../../connect.php");

  $nota = isset($_POST['nota']) ? floatval($_POST['nota']) : null;
  $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : null;
  $avaliacao_id = isset($_POST['avaliacao_id']) ? intval($_POST['avaliacao_id']) : null;

  if ($nota === null || $mensagem === null || $avaliacao_id === null) {
      die('Dados inválidos.');
  }


  $connection = connect_db();

  $stmt = $connection->prepare("UPDATE avaliacao SET nota = ?, mensagem = ? WHERE id = ?");
  $stmt->bind_param("dsi", $nota, $mensagem, $avaliacao_id);

  if ($stmt->execute()) {
      if ($stmt->execute()) {
          echo "Avaliação inserida com sucesso!";
      } else {
          echo "Erro ao vincular a avaliação ao álbum: " . $stmt->error;
      }

      $stmt->close();
  } else {
      echo "Erro ao inserir a avaliação: " . $stmt->error;
  }

  $stmt->close();
  $connection->close();
?>
