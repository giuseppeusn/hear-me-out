xa<?php
if (isset($_GET['id'])) {
    $oMysql = connect_db();
    $query = "DELETE FROM usuario WHERE id = ".$_GET['id'];
    $oMysql->query($query);
    header('location: index.php');
    
}
?>