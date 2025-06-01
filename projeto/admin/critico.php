<?php
    function formatarCPF($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' .
                substr($cpf, 3, 3) . '.' .
                substr($cpf, 6, 3) . '-' .
                substr($cpf, 9, 2);
        }
        return $cpf;
    }
?>


<div class="col-12">
    <div class="card shadow rounded-4 p-4">
        <div class="d-flex flex-column flex-lg-row gap-4 align-items-center">
            <img src="https://static.vecteezy.com/ti/vetor-gratis/p1/9292244-default-avatar-icon-vector-of-social-media-user-vetor.jpg" alt="Imagem" class="rounded-3" style="width: 220px; height: 220px; object-fit: cover;">
            <div>
                <p class='text-uppercase mb-1' style='font-size: 12px;'>Crítico</p>
                <h2 class="card-title"><?php echo htmlspecialchars($critico['nome']); ?></h2>
                <p class="card-text mb-2"><span class="label">Email:</span> <?php echo htmlspecialchars($critico['email']); ?></p>
                <p class="card-text mb-2"><span class="label">CPF: </span><?php echo htmlspecialchars(formatarCPF($critico['cpf'])); ?></p>
                <p class="card-text mb-2"><span class="label">Data de nascimento:</span>
                    <?php 
                        echo htmlspecialchars(
                            (new DateTime($critico['data_nasc']))->format("d/m/Y")
                        ); 
                    ?>
                </p>
                <p class="card-text mb-2"><span class="label">Gênero:</span> 
                    <?php
                        switch ($critico['genero']) {
                            case 'M': echo "Masculino"; break;
                            case 'F': echo "Feminino"; break;
                            default: echo "Indefinido"; break;
                        }
                    ?>
                </p>
                <p class="card-text mb-2"><span class="label">Portfólio:</span> 
                    <?php echo $critico['site'] ? "<a href='".htmlspecialchars($critico['site'])."' target='_blank' class='text-info'>".htmlspecialchars($critico['site'])."</a>" : "Não informado"; ?>
                </p>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="label">Biografia:</h5>
            <p class="card-text"><?php echo htmlspecialchars($critico['biografia']); ?></p>
        </div>

        <div class="mt-4">
            <a href="?id=<?php echo $critico['id']; ?>&aprovado=1&tipo=critico" class="cs-btn confirm aprovar-link w-100">
                Aprovar
            </a>
        </div>
    </div>
</div>
    