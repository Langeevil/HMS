<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_especialidade') {
    $response = saveResource('especialidades', $_POST);

    if ($response['success']) {
        set_flash('success', 'Especialidade atualizada com sucesso.');
        header('Location: ' . url($basePath, 'pages/especialidades/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel atualizar a especialidade.';
}

$response = fetchResourceList('especialidades');
$especialidades = is_array($response['data'] ?? null) ? $response['data'] : [];
render_head('HMS - Especialidades', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'especialidades'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Especialidades</h1><p class="text-muted mb-0">Cadastro de areas clinicas em um layout mais atual e consistente.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/especialidades/form.php')) ?>" class="btn btn-primary">Nova especialidade</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Nome</th><th>Descricao</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($especialidades === []): ?><?php empty_table_row(3, 'Nenhuma especialidade carregada. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($especialidades as $especialidade): ?>
                <tr><td><?= h($especialidade['nome'] ?? '-') ?></td><td><?= h($especialidade['descricao'] ?? '-') ?></td><td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-especialidade" data-record="<?= json_attr($especialidade) ?>" data-bs-toggle="modal" data-bs-target="#editEspecialidadeModal">Editar</button> <a href="<?= h($especialidade['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editEspecialidadeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/especialidades/listar.php')) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar especialidade</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_especialidade">
                        <input type="hidden" name="codespecialidade" id="edit-especialidade-codigo">
                        <div class="mb-3">
                            <label for="edit-especialidade-nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="edit-especialidade-nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-especialidade-descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" name="descricao" id="edit-especialidade-descricao" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.js-edit-especialidade').forEach(function (button) {
            button.addEventListener('click', function () {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-especialidade-codigo').value = record.codespecialidade || '';
                document.getElementById('edit-especialidade-nome').value = record.nome || '';
                document.getElementById('edit-especialidade-descricao').value = record.descricao || '';
            });
        });
    </script>
<?php render_scripts(); ?>
