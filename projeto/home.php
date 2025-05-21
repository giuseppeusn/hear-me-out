<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="stylesheet" href="styles/card.css">
  <link rel="stylesheet" href="styles/carousel.css">
  <link rel="stylesheet" href="styles/home.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</head>
<body>
  <div class="cs-container">
    <div class="content">
      <div class="home-banner">
        <div class="home-banner-content">
          <h1>Encontre músicas, álbuns ou artistas</h1>
          <?php
            // include("./components/search.php");
            include __DIR__ . "/components/search.php";

            echo search(false);
          ?>
        </div>
        <video autoplay muted loop class="video-banner">
          <source src="assets/video/guittar-banner.mp4" type="video/mp4" >
          Seu navegador não suporta o vídeo.
        </video>
      </div>
      <div class="swiper mySwiper1 mt-5">
        <h2 class="text-white mb-3">Sugestões musicais para você</h2>
        <div class="swiper-wrapper">
          <?php
            $connection = connect_db(); 
            $query = "SELECT * FROM view_musicas_com_nomes ORDER BY RAND()";
            $resultado = $connection->query($query);
            include ("./components/cauroselCard.php");

            if ($resultado && $resultado->num_rows > 0) {
              while ($data = $resultado->fetch_object()) {
                echo '<div class="swiper-slide">';
                echo cauroselCard($data->nome_musica, $data->nome_artista, $data->capa ?? '#', '/hear-me-out/projeto/paginaUser/musica.php?id=' . $data->id_musica . '');
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
        <h2 class="text-white mb-3">Álbuns em alta</h2>
        <div class="swiper-wrapper">
          <?php
            $query = "SELECT  * FROM view_albuns_com_nomes ORDER BY RAND()";
            $resultado = $connection->query($query);

            if ($resultado && $resultado->num_rows > 0) {
              while ($data = $resultado->fetch_object()) {
                echo '<div class="swiper-slide">';
                echo cauroselCard($data->nome_album, $data->nome_artista, $data->capa ?? '#', '/hear-me-out/projeto/paginaUser/album.php?id=' . $data->album_id . '');
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
