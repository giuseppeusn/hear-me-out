<?php
session_start();

if (isset($_SESSION['mensagem'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $_SESSION['mensagem'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button></div>';
    unset($_SESSION['mensagem']);
}
?>