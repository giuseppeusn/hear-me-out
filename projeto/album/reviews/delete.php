<?php
  $id = $_GET['id'] ?? null;

  if (!$id) {
      die('ID do comentário não foi enviado.');
  }

  include_once("../../connect.php");

  $connection = connect_db();


  $stmt = $connection->prepare("DELETE FROM avaliacao_album WHERE id_avaliacao = ?");
  $stmt->bind_param("i", $id);

  if (!$stmt->execute()) {
    die("Erro ao excluir a avaliação do álbum: " . $stmt->error);
  }

  $stmt->close();

  $stmt2 = $connection->prepare("DELETE FROM avaliacao WHERE id = ?");
  $stmt2->bind_param("i", $id);

  if (!$stmt2->execute()) {
    die("Erro ao excluir a avaliação: " . $stmt2->error);
  }

  $stmt2->close();

  echo "Avaliação excluída com sucesso.";
  $connection->close();
?>
