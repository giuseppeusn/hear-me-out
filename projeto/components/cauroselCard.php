<?php 
  function cauroselCard($name, $artist, $image, $link) {
    return '
    <a href="' . $link . '" class="custom-card card-hover">
      <img class="custom-card-img" src="' . $image . '" alt="' . $name . '">
      <div class="card-text">
        <h5>' . $name . '</h5>
        <p>' . $artist . '</p>
      </div>
    </a>
    ';
  }
?>