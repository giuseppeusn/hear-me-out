<div class="section mt-4 align-items-center">
  <h4 class="section-title text-center">Avaliações</h4>
  <div class="rate-wrapper">
    <?php
      include_once "renderStars.php";
      include_once "renderReviews.php";

      $notaUsuario = number_format($avaliacoes['medias']['usuario'], 1) ?? 0;
      $quantidadeUsuario = $avaliacoes['quantidades']['usuario'] ?? 0;

      if ($quantidadeUsuario == 0) {
        echo '<div class="rate-btn no-reviews">
          <p class="rate-title">Usuários</p>
          <p class="no-reviews-text">Sem avaliações</p>';
          
        echo renderStars(0);

        echo '<p class="rate-text">
            Baseado em 0 avaliações
          </p>
        </div>';
      } else {
        echo '<button class="rate-btn" onclick="openUserReviews()" type="button">
          <p class="rate-title">Usuários</p>
          <div class="rate">
            <span class="main">'.$notaUsuario.'</span>
            <span>/5</span>
          </div>';

        echo renderStars($notaUsuario);

        echo '<p class="rate-text">
          Baseado em ' . $quantidadeUsuario . ' ' . 
          ($quantidadeUsuario != 1 ? 'avaliações' : 'avaliação') . '
        </p>
      </button>';
      }
    ?>
    <?php
      $notaCritico = number_format($avaliacoes['medias']['critico'], 1) ?? 0;
      $quantidadeCritico = $avaliacoes['quantidades']['critico'] ?? 0;

      if ($quantidadeCritico == 0) {
        echo '<div class="rate-btn">
          <p class="rate-title">Crítica</p>
          <p class="no-reviews-text">Sem avaliações</p>';

        echo renderStars(0);

        echo '<p class="rate-text">
            Baseado em 0 avaliações
          </p>
        </div>';

      } else {
        echo '<button class="rate-btn" onclick="openCriticismReviews()" type="button">
          <p class="rate-title">Crítica</p>
          <div class="rate">
            <span class="main">'.$notaCritico.'</span>
            <span>/5</span>
          </div>';
          
        echo renderStars($notaCritico);
          
        echo '<p class="rate-text">
          Baseado em ' . $quantidadeCritico . ' ' . 
          ($quantidadeCritico != 1 ? 'avaliações' : 'avaliação') . '
        </p>
      </button>';
      }
    ?>
  </div>
  <?php
    $loggedIn = isset($_SESSION['id']) && isset($_SESSION['permissao']);

    if (!$loggedIn) {
      echo '<p>Você precisa de uma conta para fazer uma avaliação.
        <a href="../login.php" class="link">Clique para entrar</a>.
      </p>';
    } else if ($loggedIn && $avaliacoes['minhaAvaliacao']) {
      echo '<button class="new-review-btn" onclick="openUpdateFormReview()" type="button">
        <span>Ver avaliação</span>
      </button>';
    } else if ($_SESSION['permissao'] != 'artista') {
      echo '<button class="new-review-btn" onclick="openFormReview()" type="button">
        <span>Adicionar avaliação</span>
      </button>';
    }
  ?>
</div>

