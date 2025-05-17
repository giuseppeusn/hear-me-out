<?php 
  function genderCard($name, $image, $link) {
    return '
    <a hred="' . $link . '" class="gender-card">
      <img class="custom-card-img" src="' . $image . '" alt="' . $name . '">
      <div class="card-text">
        <h5>' . $name . '</h5>
      </div>
    </a>
    ';
  }
?>