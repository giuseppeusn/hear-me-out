<div class="col-12">
    <div class="card shadow rounded-4 p-4">
        <div class="d-flex flex-column flex-lg-row gap-4 align-items-center">
            <img src="<?php echo $artista['imagem']; ?>" alt="Imagem" class="rounded-3" style="width: 220px; height: 220px; object-fit: cover;">
            <div>
                <p class='text-uppercase mb-1' style='font-size: 12px;'>Artista</p>
                <h2 class="card-title"><?php echo htmlspecialchars($artista['nome']); ?></h2>
                <p class="card-text mb-2"><span class="label">Email:</span> <?php echo htmlspecialchars($artista['email']); ?></p>
                <p class="card-text mb-2"><span class="label">Data de formação:</span> 
                    <?php 
                        echo htmlspecialchars(
                            (new DateTime($artista['data_formacao']))->format("d/m/Y")
                        ); 
                    ?>
                </p>
                <p class="card-text mb-2"><span class="label">País:</span> <?php echo htmlspecialchars($artista['pais']); ?></p>
                <p class="card-text mb-2"><span class="label">Site oficial:</span> 
                    <?php echo $artista['site_oficial'] ? "<a href='{$artista['site_oficial']}' target='_blank' class='text-info'>".$artista['site_oficial']."</a>" : "Não informado"; ?>
                </p>
                <p class="card-text mb-2"><span class="label">Gênero musical:</span> <?php echo htmlspecialchars($artista['genero']); ?></p>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="label">Biografia:</h5>
            <p class="card-text"><?php echo htmlspecialchars($artista['biografia']); ?></p>
        </div>

        <div class="mt-4">
            <a href="?id=<?php echo $artista['id']; ?>&aprovado=1&tipo=artista" class="cs-btn confirm aprovar-link w-100">
                Aprovar
            </a>
        </div>
    </div>
</div>

