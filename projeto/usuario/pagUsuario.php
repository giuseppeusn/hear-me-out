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

if (!isset($_SESSION['authenticated']) || !isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado como usuário.");
}

$id_user = intval($_SESSION['id_usuario']);

$conexao = connect_db();
$queryusuario = "SELECT * FROM usuario WHERE id = $id_user";
$resultadoUsuario = $conexao->query($queryusuario);
$usuario = $resultadoUsuario->fetch_assoc();

if ($resultadoUsuario && $resultadoUsuario->num_rows > 0) {
    echo "
<div class='container rounded bg-white mt-5 mb-5'>
    <div class='row'>
        <div class='col-md-3 border-right'>
            <div class='d-flex flex-column align-items-center text-center p-3 py-5'>
                <img class='rounded-circle mt-5' width='150px' src='https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg'>
                <span class='font-weight-bold'>{$usuario['nome']}</span>
                <span class='text-black-50'>{$usuario['email']}</span>
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
                    <input type='hidden' name='id_usuario' value='{$usuario['id']}'> <div class='col-md-6'><label class='labels'>Nome</label><input type='text' class='form-control' placeholder='first name' value='{$usuario['nome']}' name='nome' disabled></div>
                </div>
                <div class='row mt-3'>
                    <div class='col-md-12'><label class='labels'>Email:</label><input type='email' class='form-control' placeholder='email' value='{$usuario['email']}' name='email' disabled></div>
                    <div class='col-md-12'><label class='labels'>Data de Nascimento: </label><input type='date' class='form-control' placeholder='data de nascimento' value='{$usuario['data_nasc']}' name='data_nasc' disabled></div>
                    <div class='col-md-12'><label class='labels'>Genero:</label><input type='text' class='form-control' placeholder='genero' value='{$usuario['genero']}' name='genero' disabled></div>
                    <div class='col-md-12'><label class='labels'>CPF:</label><input type='text' class='form-control' placeholder='cpf' value='{$usuario['cpf']}' name='cpf' disabled></div> </form>
                </div>

                <div class='mt-5 text-center'>
                  <button id='btnSalvar' class='btn btn-primary profile-button' type='button' style='display:none;'>Salvar Perfil</button>
                  </button> <button id='btnEditar' class='btn btn-secondary mt-3' type='button'>Alterar configurações</button> </div>
                      <div class='mt-3 text-center'>
                          <button id='btnExcluir' class='btn btn-danger'>Excluir Conta</button>
                        </div>

            </div>
        </div>
        <div class='col-md-4'>
            <div class='p-3 py-5'>
                <div class='d-flex justify-content-between align-items-center experience'>
                    <span class='border px-3 p-1 add-experience'><i class='fa fa-plus'></i>&nbsp;Experience</span>
                </div><br>

            </div>
        </div>
    </div>
</div>
";
} else {
    echo "Usuário não encontrado!";
    exit;
}
?>

<script>
function abrirAlterarPerfil(usuario) {
    Swal.fire({
        title: 'Editar Perfil',
        html:
            `<input id="swal-nome" class="swal2-input" placeholder="Nome" value="${usuario.nome}">` +
            `<input id="swal-email" class="swal2-input" placeholder="Email" type="email" value="${usuario.email}">` +
            `<input id="swal-data" class="swal2-input" type="date" value="${usuario.data_nasc}">` +
            `<select id="swal-genero" class="swal2-input">
                <option value="M" ${usuario.genero === 'M' ? 'selected' : ''}>Masculino</option>
                <option value="F" ${usuario.genero === 'F' ? 'selected' : ''}>Feminino</option>
                <option value="I" ${usuario.genero === 'I' ? 'selected' : ''}>Prefiro não informar</option>
            </select>` +
            `<input id="swal-cpf" class="swal2-input" placeholder="CPF" value="${usuario.cpf}" readonly>` +
            `<input id="swal-senha" class="swal2-input" type="password" placeholder="Nova Senha (opcional)">`,
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

            if (!nome || !email || !data_nasc || !genero || !cpf) {
                return Swal.showValidationMessage('Todos os campos são obrigatórios, exceto a senha.');
            }

            return {
                id_usuario: usuario.id,
                nome,
                email,
                data_nasc,
                genero,
                cpf,
                senha
            };
        }
    }).then((resultado) => {
        if (resultado.isConfirmed) {
            fetch('update.php', {
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
    const usuario = {
        id: <?= $usuario['id'] ?>,
        nome: "<?= addslashes($usuario['nome']) ?>",
        email: "<?= $usuario['email'] ?>",
        data_nasc: "<?= $usuario['data_nasc'] ?>",
        genero: "<?= $usuario['genero'] ?>",
        cpf: "<?= $usuario['cpf'] ?>"
    };

    document.getElementById("btnEditar").addEventListener("click", () => {
        abrirAlterarPerfil(usuario);
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
                    body: JSON.stringify({ id_usuario: usuario.id })
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

    // Oculta botão antigo se necessário
    document.getElementById("btnSalvar").style.display = "none";
});
</script>



</body> </html>