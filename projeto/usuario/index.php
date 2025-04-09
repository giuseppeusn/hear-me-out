<?php
  include("../header.php");
  include("../connect.php");

  if(isset($_GET["page"])) {
    if($_GET["page"] == 1) {
      include("insert.php");
    } else if($_GET["page"] == 2) {
      include("update.php");
    } else if($_GET["page"] == 3) {
      include("delete.php");
    } else {
      include("usuario.php");
    }
  } else {
    include("usuario.php");
  }

?>