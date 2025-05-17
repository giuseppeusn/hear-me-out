<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="stylesheet" href="styles/card.css">
  <link rel="stylesheet" href="styles/carousel.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body>
  <div class="container">
    <div class="swiper mySwiper1">
      <h2 class="text-white">Sugestões musicais para você</h2>
      <div class="swiper-wrapper">
        <?php
          $connection = connect_db(); 
          $query = "SELECT * FROM view_musicas_com_nomes ORDER BY RAND()";
          $resultado = $connection->query($query);
          include ("./components/card.php");

          if ($resultado && $resultado->num_rows > 0) {
            while ($data = $resultado->fetch_object()) {
              echo '<div class="swiper-slide">';
              echo card($data->nome_musica, $data->nome_artista, $data->capa ?? '#', '#');
              echo '</div>';
            }
          } else {
            echo "<p>Nenhum álbum encontrado.</p>";
          }
        ?>
      </div>
      <div class="swiper-button-next swiper-button-next-1"></div>
      <div class="swiper-button-prev swiper-button-prev-1"></div>
    </div>
    <div class="swiper mySwiper2 mt-5">
      <h2 class="text-white">Álbuns em alta</h2>
      <div class="swiper-wrapper">
        <?php
          $query = "SELECT * FROM view_albuns_com_nomes ORDER BY RAND()";
          $resultado = $connection->query($query);

          if ($resultado && $resultado->num_rows > 0) {
            while ($data = $resultado->fetch_object()) {
              echo '<div class="swiper-slide">';
              echo card($data->nome_album, $data->nome_artista, $data->capa ?? '#', '#');
              echo '</div>';
            }
          } else {
            echo "<p>Nenhum álbum encontrado.</p>";
          }
        ?>
      </div>
      <div class="swiper-button-next swiper-button-next-2"></div>
      <div class="swiper-button-prev swiper-button-prev-2"></div>
    </div>
    <div class="mt-5">
      <h2 class="text-white">Gêneros</h2>
      <div class="">
        <?php

          $query = "SELECT genero, imagem
            FROM (
              SELECT 
                genero,
                imagem,
                ROW_NUMBER() OVER (PARTITION BY genero ORDER BY RAND()) AS ordem
              FROM artista
              WHERE imagem IS NOT NULL AND genero IS NOT NULL
            ) AS sub
            WHERE ordem = 1;";
          $resultado = $connection->query($query);

          include ("./components/genderCard.php");

          if ($resultado && $resultado->num_rows > 0) {
            while ($data = $resultado->fetch_object()) {
              echo genderCard($data->genero, $data->imagem ?? '#', '#');
            }
          } else {
            echo "<p>Nenhum gênero encontrado.</p>";
          }
        ?>
      </div>
    </div>
  </div>
  <script>
    new Swiper(".mySwiper1", {
      loop: true,
      slidesPerView: 4,
      spaceBetween: 40,
      navigation: {
        nextEl: ".swiper-button-next-1",
        prevEl: ".swiper-button-prev-1",
      },
      breakpoints: {
        0: { slidesPerView: 1 },
        576: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        992: { slidesPerView: 4 },
      }
    });

    new Swiper(".mySwiper2", {
      loop: true,
      slidesPerView: 4,
      spaceBetween: 40,
      navigation: {
        nextEl: ".swiper-button-next-2",
        prevEl: ".swiper-button-prev-2",
      },
      breakpoints: {
        0: { slidesPerView: 1 },
        576: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        992: { slidesPerView: 4 },
      }
    });
  </script>
</body>
</html>
