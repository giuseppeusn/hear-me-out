<?php
if (isset($_GET['id'])) {
    $oMysql = connect_db();
    $query = "DELETE FROM critico WHERE id = " . $_GET['id'];
    $oMysql->query($query);
    header('location: index.php');
}
?>