<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_leito') {
    $response = saveResource('leitos', $_POST);

    if ($response['success']) {
        set_flash('success', 'Leito atualizado com sucesso.');
        header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel atualizar o leito.';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $response = deleteResource('leitos', $_GET['delete_id']);

    if ($response['success']) {
        set_flash('success', 'Leito excluido com sucesso.');
        header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel excluir o leito.';
}

$quartosResponse = fetchResourceList('quartos');
$quartos = is_array($quartosResponse['data'] ?? null) ? $quartosResponse['data'] : [];
$response = fetchResourceList('leitos');
$leitos = is_array($response['data'] ?? null) ? $response['data'] : [];
foreach ($leitos as &$leito) {
    $leito['excluir_url'] = url($basePath, 'pages/leitos/listar.php?delete_id=' . ($leito['codleito'] ?? ''));
}
unset($leito);
render_head('HMS - Leitos', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'leitos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Leitos</h1><p class="text-muted mb-0">Monitoramento administrativo com acabamento mais contemporaneo.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Codigo</th><th>Quarto</th><th>Ala</th><th>Status</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($leitos === []): ?><?php empty_table_row(5, 'Nenhum leito carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($leitos as $leito): ?>
                <tr><td><?= h($leito['codleito'] ?? '-') ?></td><td><?= h($leito['quarto']['numero'] ?? '-') ?></td><td><?= h($leito['quarto']['ala']['nome'] ?? '-') ?></td><td><span class="badge bg-success"><?= h($leito['status'] ?? 'Disponivel') ?></span></td><td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-leito" data-record="<?= json_attr($leito) ?>" data-bs-toggle="modal" data-bs-target="#editLeitoModal">Editar</button> <a href="<?= h($leito['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editLeitoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar leito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_leito">
                        <input type="hidden" name="codleito" id="edit-leito-codigo">
                        <div class="mb-3">
                            <label for="edit-leito-status" class="form-label">Status</label>
                            <input type="text" class="form-control" name="status" id="edit-leito-status" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-leito-codquarto" class="form-label">Quarto</label>
                            <select class="form-select" name="codquarto" id="edit-leito-codquarto" required>
                                <option value="">Selecione...</option>
<?php foreach ($quartos as $quarto): ?>
                                <option value="<?= h($quarto['codquarto'] ?? '') ?>"><?= h(($quarto['numero'] ?? '-') . ' - ' . ($quarto['ala']['nome'] ?? '-')) ?></option>
<?php endforeach; ?>
                            </select>
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
        document.querySelectorAll('.js-edit-leito').forEach(function (button) {
            button.addEventListener('click', function () {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-leito-codigo').value = record.codleito || '';
                document.getElementById('edit-leito-status').value = record.status || '';
                document.getElementById('edit-leito-codquarto').value = record.quarto?.codquarto || '';
            });
        });
    </script>
<?php render_scripts(); ?>
