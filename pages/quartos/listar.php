<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_quarto') {
    $response = saveResource('quartos', $_POST);

    if ($response['success']) {
        set_flash('success', 'Quarto atualizado com sucesso.');
        header('Location: ' . url($basePath, 'pages/quartos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel atualizar o quarto.';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $response = deleteResource('quartos', $_GET['delete_id']);

    if ($response['success']) {
        set_flash('success', 'Quarto excluido com sucesso.');
        header('Location: ' . url($basePath, 'pages/quartos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel excluir o quarto.';
}

$alasResponse = fetchResourceList('alas');
$alas = is_array($alasResponse['data'] ?? null) ? $alasResponse['data'] : [];
$response = fetchResourceList('quartos');
$quartos = is_array($response['data'] ?? null) ? $response['data'] : [];
foreach ($quartos as &$quarto) {
    $quarto['excluir_url'] = url($basePath, 'pages/quartos/listar.php?delete_id=' . ($quarto['codquarto'] ?? ''));
}
unset($quarto);
render_head('HMS - Quartos', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'quartos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Quartos</h1><p class="text-muted mb-0">Tabela modernizada sem alterar a disposicao dos dados operacionais.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/quartos/form.php')) ?>" class="btn btn-primary">Novo quarto</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Numero</th><th>Tipo</th><th>Ala</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($quartos === []): ?><?php empty_table_row(4, 'Nenhum quarto carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($quartos as $quarto): ?>
                <tr><td><?= h($quarto['numero'] ?? '-') ?></td><td><?= h($quarto['tipo'] ?? '-') ?></td><td><?= h($quarto['ala']['nome'] ?? '-') ?></td><td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-quarto" data-record="<?= json_attr($quarto) ?>" data-bs-toggle="modal" data-bs-target="#editQuartoModal">Editar</button> <a href="<?= h($quarto['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editQuartoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/quartos/listar.php')) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar quarto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_quarto">
                        <input type="hidden" name="codquarto" id="edit-quarto-codigo">
                        <div class="mb-3">
                            <label for="edit-quarto-numero" class="form-label">Numero</label>
                            <input type="number" class="form-control" name="numero" id="edit-quarto-numero" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-quarto-tipo" class="form-label">Tipo</label>
                            <input type="text" class="form-control" name="tipo" id="edit-quarto-tipo">
                        </div>
                        <div class="mb-3">
                            <label for="edit-quarto-codala" class="form-label">Ala</label>
                            <select class="form-select" name="codala" id="edit-quarto-codala" required>
                                <option value="">Selecione...</option>
<?php foreach ($alas as $ala): ?>
                                <option value="<?= h($ala['codala'] ?? '') ?>"><?= h($ala['nome'] ?? '') ?></option>
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
        document.querySelectorAll('.js-edit-quarto').forEach(function (button) {
            button.addEventListener('click', function () {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-quarto-codigo').value = record.codquarto || '';
                document.getElementById('edit-quarto-numero').value = record.numero || '';
                document.getElementById('edit-quarto-tipo').value = record.tipo || '';
                document.getElementById('edit-quarto-codala').value = record.ala?.codala || '';
            });
        });
    </script>
<?php render_scripts(); ?>
