<?php
session_start(); 
include_once("../header.php"); 
include_once("../connect.php"); 

if (!isset($_SESSION['id'])) {
    die("Você precisa estar logado para acessar esta página.");
}

$conexao = connect_db();
$dadosUsuario = null;
$tipoUsuario = 'usuario'; 
$id = intval($_SESSION['id']);


$tabelas = ['usuario', 'critico', 'artista'];
foreach ($tabelas as $tabela) {
    $query = "SELECT * FROM $tabela WHERE id = $id";
    $resultado = $conexao->query($query);

    if ($resultado && $resultado->num_rows > 0) {
        $dadosUsuario = $resultado->fetch_assoc();
        $tipoUsuario = $tabela;
        break;
    }
}

if (!$dadosUsuario) {
    die("Usuário não encontrado!");
}


$cpf = $dadosUsuario['cpf'] ?? '';
$genero_user_select = $dadosUsuario['genero'] ?? ''; 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
<body>
<div class="container rounded bg-white mt-5 mb-5 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-right">Configurações de Perfil</h4>
    </div>

    <div class="row mt-2">
        <form id="formExibicaoPerfil" class="w-100">
            <div class="col-md-12">
                <label class="labels">Nome</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($dadosUsuario['nome']) ?>" name="nome" disabled>
            </div>

            <div class="col-md-12 mt-3">
                <label class="labels">Email:</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($dadosUsuario['email']) ?>" name="email" disabled>
            </div>

            <?php if ($tipoUsuario === 'usuario' || $tipoUsuario === 'critico'): ?>
                <div class="col-md-12 mt-3">
                    <label class="labels">Data de Nascimento:</label>
                    <input type="date" class="form-control" value="<?= $dadosUsuario['data_nasc'] ?>" name="data_nasc" disabled>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="labels">Gênero:</label>
                    <input type="text" class="form-control" value="<?php
                        if ($dadosUsuario['genero'] == 'M') echo 'Masculino';
                        elseif ($dadosUsuario['genero'] == 'F') echo 'Feminino';
                        else echo 'Indefinido';
                    ?>" name="genero_exibicao" disabled>
                </div>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'critico' || $tipoUsuario === 'artista'): ?>
                <div class="col-md-12 mt-3">
                    <label class="labels">Biografia:</label>
                    <textarea class="form-control" name="biografia" disabled><?= htmlspecialchars($dadosUsuario['biografia']) ?></textarea>
                </div>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'critico'): ?>
                <div class="col-md-12 mt-3">
                    <label class="labels">Site:</label>
                    <input type="url" class="form-control" value="<?= htmlspecialchars($dadosUsuario['site']) ?>" name="site" disabled>
                </div>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'artista'): ?>
                <div class="col-md-12 mt-3">
                    <label class="labels">Data de Formação:</label>
                    <input type="date" class="form-control" value="<?= $dadosUsuario['data_formacao'] ?>" name="data_formacao" disabled>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="labels">País:</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($dadosUsuario['pais']) ?>" name="pais" disabled>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="labels">Site Oficial:</label>
                    <input type="url" class="form-control" value="<?= htmlspecialchars($dadosUsuario['site_oficial']) ?>" name="site_oficial" disabled>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="labels">Gênero Musical:</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($dadosUsuario['genero']) ?>" name="genero_musical" disabled>
                </div>
            <?php endif; ?>

            </form>
    </div>

    <div class="mt-5 text-center">
        <button id="btnEditar" class="btn btn-primary profile-button" type="button">Editar Perfil</button>
    </div>
    <div class="mt-3 text-center">
        <button id="btnExcluir" class="btn btn-danger">Excluir Conta</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnEditar = document.getElementById('btnEditar');
    const btnExcluir = document.getElementById('btnExcluir');
    const tipoUsuario = "<?= $tipoUsuario ?>";
    const dadosUsuario = <?= json_encode($dadosUsuario) ?>; 

    
    btnEditar.addEventListener('click', function() {
        let htmlContent = `
            <input id="swal-nome" class="swal2-input" placeholder="Nome" value="${dadosUsuario.nome || ''}">
            <input id="swal-email" class="swal2-input" placeholder="Email" type="email" value="${dadosUsuario.email || ''}">
        `;

        if (tipoUsuario === 'usuario' || tipoUsuario === 'critico') {
            htmlContent += `
                <input id="swal-data_nasc" class="swal2-input" placeholder="Data de Nascimento" type="date" value="${dadosUsuario.data_nasc || ''}">
                <select id="swal-genero" class="swal2-select">
                    <option value="M" ${dadosUsuario.genero === 'M' ? 'selected' : ''}>Masculino</option>
                    <option value="F" ${dadosUsuario.genero === 'F' ? 'selected' : ''}>Feminino</option>
                    <option value="I" ${dadosUsuario.genero === 'I' ? 'selected' : ''}>Indefinido</option>
                </select>
            `;
        }

        if (tipoUsuario === 'critico' || tipoUsuario === 'artista') {
            htmlContent += `
                <textarea id="swal-biografia" class="swal2-textarea" placeholder="Biografia">${dadosUsuario.biografia || ''}</textarea>
            `;
        }

        if (tipoUsuario === 'critico') {
            htmlContent += `
                <input id="swal-site" class="swal2-input" placeholder="Site" type="url" value="${dadosUsuario.site || ''}">
            `;
        }

        if (tipoUsuario === 'artista') {
            htmlContent += `
                <input id="swal-data_formacao" class="swal2-input" placeholder="Data de Formação" type="date" value="${dadosUsuario.data_formacao || ''}">
                <input id="swal-pais" class="swal2-input" placeholder="País" value="${dadosUsuario.pais || ''}">
                <input id="swal-site_oficial" class="swal2-input" placeholder="Site Oficial" type="url" value="${dadosUsuario.site_oficial || ''}">
                <input id="swal-genero_musical" class="swal2-input" placeholder="Gênero Musical" value="${dadosUsuario.genero || ''}">
            `;
        }

        htmlContent += `
            <input id="swal-senha" class="swal2-input" placeholder="Nova Senha (opcional)" type="password">
        `;

        Swal.fire({
            title: 'Editar Perfil',
            html: htmlContent,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Salvar Alterações',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const updatedData = {
                    id: dadosUsuario.id,
                    tipoUsuario: tipoUsuario, 
                    nome: document.getElementById('swal-nome').value,
                    email: document.getElementById('swal-email').value
                };

                if (tipoUsuario === 'usuario' || tipoUsuario === 'critico') {
                    updatedData.data_nasc = document.getElementById('swal-data_nasc').value;
                    updatedData.genero = document.getElementById('swal-genero').value;
                    updatedData.cpf = dadosUsuario.cpf || ''; 
                }

                if (tipoUsuario === 'critico' || tipoUsuario === 'artista') {
                    updatedData.biografia = document.getElementById('swal-biografia').value;
                }

                if (tipoUsuario === 'critico') {
                    updatedData.site = document.getElementById('swal-site').value;
                }

                if (tipoUsuario === 'artista') {
                    updatedData.data_formacao = document.getElementById('swal-data_formacao').value;
                    updatedData.pais = document.getElementById('swal-pais').value;
                    updatedData.site_oficial = document.getElementById('swal-site_oficial').value;
                    updatedData.genero = document.getElementById('swal-genero_musical').value; 
                }

                const novaSenha = document.getElementById('swal-senha').value;
                if (novaSenha) {
                    updatedData.senha = novaSenha;
                }

                
                if (!updatedData.nome || !updatedData.email) {
                    Swal.showValidationMessage('Nome e Email são obrigatórios!');
                    return false;
                }
                

                return updatedData;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const dataToSave = result.value; 

                let urlAtualizacao = '';
                switch(tipoUsuario) {
                    case 'usuario':
                        urlAtualizacao = 'update.php';
                        break;
                    case 'critico':
                        urlAtualizacao = 'update_critico.php';
                        break;
                    case 'artista':
                        urlAtualizacao = 'update_artista.php';
                        break;
                    default:
                        Swal.fire('Erro!', 'Tipo de usuário inválido.', 'error');
                        return;
                }

                fetch(urlAtualizacao, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSave)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().catch(() => {
                            return response.text().then(text => { throw new Error(text) });
                        }).then(errorData => {
                            if (errorData && errorData.error) {
                                throw new Error(errorData.error);
                            } else if (typeof errorData === 'string') {
                                throw new Error(errorData);
                            } else {
                                throw new Error('Erro desconhecido na resposta do servidor.');
                            }
                        });
                    }
                    return response.json(); 
                })
                .then(result => {
                    if (result.success) {
                        Swal.fire(
                            'Salvo!',
                            result.message || 'Seu perfil foi atualizado com sucesso.',
                            'success'
                        ).then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            result.error || 'Não foi possível atualizar o perfil.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Erro ao salvar:', error);
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao conectar ou processar a resposta do servidor: ' + error.message,
                        'error'
                    );
                });
            }
        });
    });

    
    btnExcluir.addEventListener('click', function() {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter esta ação!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir conta!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    id: dadosUsuario.id,
                    tipo: tipoUsuario
                };

                fetch('delete.php', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json(); 
                })
                .then(result => {
                    if (result.success) {
                        Swal.fire(
                            'Excluído!',
                            result.message || 'Sua conta foi excluída com sucesso.',
                            'success'
                        ).then(() => {
                            window.location.href = '/hear-me-out/projeto/login.php'; 
                        });
                    } else {
                        Swal.fire(
                            'Erro!',
                            result.error || 'Não foi possível excluir a conta.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Erro ao excluir:', error);
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir a conta: ' + error.message,
                        'error'
                    );
                });
            }
        });
    });
});
</script>
</body>
</html>