<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles/usuario.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Seu perfil</title>
    </head>
<body> <?php
session_start();

include_once("../connect.php");

if (!isset($_SESSION['authenticated'])) {
    die("Você precisa estar logado.");
}

$conexao = connect_db();
$userData = null;
$isCritico = false;

if (isset($_SESSION['id_usuario'])) {
    $id = intval($_SESSION['id_usuario']);
    $query = "SELECT * FROM usuario WHERE id = $id";
    $resultado = $conexao->query($query);
    if ($resultado && $resultado->num_rows > 0) {
        $userData = $resultado->fetch_assoc();
    }
} 
elseif (isset($_SESSION['id_critico'])) {
    $id = intval($_SESSION['id_critico']);
    $query = "SELECT * FROM critico WHERE id = $id";
    $resultado = $conexao->query($query);
    if ($resultado && $resultado->num_rows > 0) {
        $userData = $resultado->fetch_assoc();
        $isCritico = true;
    }
}

if (!$userData) {
    echo "Usuário não encontrado!";
    exit;
}

echo "
<div class='container rounded bg-white mt-5 mb-5'>
    <div class='row'>
        <div class='col-md-3 border-right'>
            <div class='d-flex flex-column align-items-center text-center p-3 py-5'>
                <img class='rounded-circle mt-5' width='150px' src='https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg'>
                <span class='font-weight-bold'>{$userData['nome']}</span>
                <span class='text-black-50'>{$userData['email']}</span>
                <span> </span>
            </div>
        </div>
        <div class='col-md-5 border-right'>
            <div class='p-3 py-5'>
                <div class='d-flex justify-content-between align-items-center mb-3'>
                    <h4 class='text-right'>Meu Perfil</h4>
                </div>
                <div class='row mt-2'>
                <form id='formPerfil'>
                    <input type='hidden' name='id_usuario' value='{$userData['id']}'>
                    <div class='col-md-6'><label class='labels'>Nome</label><input type='text' class='form-control' placeholder='first name' value='{$userData['nome']}' name='nome' disabled></div>
                </div>
                <div class='row mt-3'>
                    <div class='col-md-12'><label class='labels'>Email:</label><input type='email' class='form-control' placeholder='email' value='{$userData['email']}' name='email' disabled></div>";

if (!$isCritico) {
    echo "<div class='col-md-12'><label class='labels'>Data de Nascimento: </label><input type='date' class='form-control' placeholder='data de nascimento' value='{$userData['data_nasc']}' name='data_nasc' disabled></div>
          <div class='col-md-12'><label class='labels'>Genero:</label><input type='text' class='form-control' placeholder='genero' value='{$userData['genero']}' name='genero' disabled></div>
          <div class='col-md-12'><label class='labels'>CPF:</label><input type='text' class='form-control' placeholder='cpf' value='{$userData['cpf']}' name='cpf' disabled></div>";
} else {
    echo "<div class='col-md-12'><label class='labels'>Biografia:</label><textarea class='form-control' placeholder='biografia' name='biografia' disabled>{$userData['biografia']}</textarea></div>
          <div class='col-md-12'><label class='labels'>Data de Nascimento: </label><input type='date' class='form-control' placeholder='data de nascimento' value='{$userData['data_nasc']}' name='data_nasc' disabled></div>
          <div class='col-md-12'><label class='labels'>Genero:</label><input type='text' class='form-control' placeholder='genero' value='{$userData['genero']}' name='genero' disabled></div>
          <div class='col-md-12'><label class='labels'>CPF:</label><input type='text' class='form-control' placeholder='cpf' value='{$userData['cpf']}' name='cpf' disabled></div>
          <div class='col-md-12'><label class='labels'>Site:</label><input type='text' class='form-control' placeholder='site' value='{$userData['site']}' name='site' disabled></div>";
}

echo "</form>
                </div>

                <div class='mt-5 text-center'>
                  <button id='btnSalvar' class='btn btn-primary profile-button' type='button' style='display:none;'>Salvar Perfil</button>
                  </button> <button id='btnEditar' class='btn btn-secondary mt-3' type='button'>Alterar configurações</button> </div>
                      <div class='mt-3 text-center'>
                          <button id='btnExcluir' class='btn btn-danger'>Excluir Conta</button>
                        </div>
            </div>
        </div>
    </div>
</div>
";
?>

<script>
function abrirAlterarPerfil(userData, isCritico) {
    let html = `
        <input id="swal-nome" class="swal2-input" placeholder="Nome" value="${userData.nome}">
        <input id="swal-email" class="swal2-input" placeholder="Email" type="email" value="${userData.email}">`;
    
    if (isCritico) {
        html += `
        <textarea id="swal-biografia" class="swal2-input" placeholder="Biografia">${userData.biografia}</textarea>
        <input id="swal-site" class="swal2-input" placeholder="Site" value="${userData.site}">`;
    }
    
    html += `
        <input id="swal-data" class="swal2-input" type="date" value="${userData.data_nasc}">
        <select id="swal-genero" class="swal2-input">
            <option value="M" ${userData.genero === 'M' ? 'selected' : ''}>Masculino</option>
            <option value="F" ${userData.genero === 'F' ? 'selected' : ''}>Feminino</option>
            <option value="I" ${userData.genero === 'I' ? 'selected' : ''}>Prefiro não informar</option>
        </select>
        <input id="swal-cpf" class="swal2-input" placeholder="CPF" value="${userData.cpf}" readonly>
        <input id="swal-senha" class="swal2-input" type="password" placeholder="Nova Senha (opcional)">`;

    Swal.fire({
        title: 'Editar Perfil',
        html: html,
        confirmButtonText: 'Salvar',
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
            const nome = document.getElementById('swal-nome').value.trim();
            const email = document.getElementById('swal-email').value.trim();
            const data_nasc = document.getElementById('swal-data').value;
            const genero = document.getElementById('swal-genero').value;
            const cpf = document.getElementById('swal-cpf').value.trim();
            const senha = document.getElementById('swal-senha').value;
            
            let data = {
                id_usuario: userData.id,
                nome,
                email,
                data_nasc,
                genero,
                cpf,
                senha
            };
            
            if (isCritico) {
                data.biografia = document.getElementById('swal-biografia').value.trim();
                data.site = document.getElementById('swal-site').value.trim();
            }

            if (!nome || !email || !data_nasc || !genero || !cpf) {
                return Swal.showValidationMessage('Todos os campos são obrigatórios, exceto a senha.');
            }
            
            if (isCritico && (!data.biografia || !data.site)) {
                return Swal.showValidationMessage('Biografia e site são obrigatórios para críticos.');
            }

            return data;
        }
    }).then((resultado) => {
        if (resultado.isConfirmed) {
            const endpoint = isCritico ? 'update_critico.php' : 'update.php';
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(resultado.value)
            })
            .then(res => res.text())
            .then(resposta => {
                Swal.fire('Sucesso!', resposta, 'success').then(() => {
                    window.location.reload();
                });
            })
            .catch(err => {
                Swal.fire('Erro!', 'Não foi possível atualizar o perfil.', 'error');
                console.error(err);
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const isCritico = <?= $isCritico ? 'true' : 'false' ?>;
    const userData = {
        id: <?= $userData['id'] ?>,
        nome: "<?= addslashes($userData['nome']) ?>",
        email: "<?= $userData['email'] ?>",
        data_nasc: "<?= $userData['data_nasc'] ?>",
        genero: "<?= $userData['genero'] ?>",
        cpf: "<?= $userData['cpf'] ?>",
        <?php if ($isCritico): ?>
        biografia: `<?= addslashes($userData['biografia']) ?>`,
        site: "<?= $userData['site'] ?>"
        <?php endif; ?>
    };

    document.getElementById("btnEditar").addEventListener("click", () => {
        abrirAlterarPerfil(userData, isCritico);
    });

    document.getElementById("btnExcluir").addEventListener("click", () => {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Essa ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir conta!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        id_usuario: userData.id,
                        is_critico: isCritico 
                    })
                })
                .then(res => res.text())
                .then(msg => {
                    Swal.fire('Excluído!', msg, 'success').then(() => {
                        window.location.href = '../logout.php';
                    });
                })
                .catch(err => {
                    Swal.fire('Erro!', 'Não foi possível excluir sua conta.', 'error');
                    console.error(err);
                });
            }
        });
    });

    document.getElementById("btnSalvar").style.display = "none";
});
</script>

</body>
</html>