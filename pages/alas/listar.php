<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_ala') {
    $response = saveResource('alas', $_POST);

    if ($response['success']) {
        set_flash('success', 'Ala atualizada com sucesso.');
        header('Location: ' . url($basePath, 'pages/alas/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel atualizar a ala.';
}

$response = fetchResourceList('alas');
$alas = is_array($response['data'] ?? null) ? $response['data'] : [];
render_head('HMS - Alas', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'alas'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Alas</h1><p class="text-muted mb-0">Organizacao setorial com identidade visual alinhada ao restante do sistema.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/alas/form.php')) ?>" class="btn btn-primary">Nova ala</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Nome</th><th>Andar</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($alas === []): ?><?php empty_table_row(3, 'Nenhuma ala carregada. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($alas as $ala): ?>
                <tr><td><?= h($ala['nome'] ?? '-') ?></td><td><?= h($ala['andar'] ?? '-') ?></td><td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-ala" data-record="<?= json_attr($ala) ?>" data-bs-toggle="modal" data-bs-target="#editAlaModal">Editar</button> <a href="<?= h($ala['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editAlaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/alas/listar.php')) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar ala</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_ala">
                        <input type="hidden" name="codala" id="edit-ala-codala">
                        <div class="mb-3">
                            <label for="edit-ala-nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="edit-ala-nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-ala-andar" class="form-label">Andar</label>
                            <input type="number" class="form-control" name="andar" id="edit-ala-andar">
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
        document.querySelectorAll('.js-edit-ala').forEach(function (button) {
            button.addEventListener('click', function () {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-ala-codala').value = record.codala || '';
                document.getElementById('edit-ala-nome').value = record.nome || '';
                document.getElementById('edit-ala-andar').value = record.andar ?? '';
            });
        });
    </script>
<?php render_scripts(); ?>
