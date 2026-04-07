<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_medico') {
    $response = saveResource('medicos', $_POST);

    if ($response['success']) {
        set_flash('success', 'Medico atualizado com sucesso.');
        header('Location: ' . url($basePath, 'pages/medicos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel atualizar o medico.';
}

$especialidadesResponse = fetchResourceList('especialidades');
$especialidades = is_array($especialidadesResponse['data'] ?? null) ? $especialidadesResponse['data'] : [];
$response = fetchResourceList('medicos');
$medicos = is_array($response['data'] ?? null) ? $response['data'] : [];
render_head('HMS - Medicos', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'medicos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Medicos</h1><p class="text-muted mb-0">Gerenciamento do corpo clinico com visual mais limpo e atual.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/medicos/form.php')) ?>" class="btn btn-primary">Novo medico</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>CRM</th><th>Nome</th><th>Especialidade</th><th>Telefone</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($medicos === []): ?><?php empty_table_row(5, 'Nenhum medico carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($medicos as $medico): ?>
                <tr><td><?= h($medico['crm'] ?? '-') ?></td><td><?= h($medico['nome'] ?? '-') ?></td><td><?= h($medico['especialidade']['nome'] ?? '-') ?></td><td><?= h($medico['telefone'] ?? '-') ?></td><td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-medico" data-record="<?= json_attr($medico) ?>" data-bs-toggle="modal" data-bs-target="#editMedicoModal">Editar</button> <a href="<?= h($medico['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editMedicoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/medicos/listar.php')) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar medico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_medico">
                        <input type="hidden" name="codmedico" id="edit-medico-codigo">
                        <div class="mb-3">
                            <label for="edit-medico-nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="edit-medico-nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-medico-crm" class="form-label">CRM</label>
                            <input type="text" class="form-control" name="crm" id="edit-medico-crm" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-medico-especialidade" class="form-label">Especialidade</label>
                            <select class="form-select" name="codespecialidade" id="edit-medico-especialidade" required>
                                <option value="">Selecione...</option>
<?php foreach ($especialidades as $especialidade): ?>
                                <option value="<?= h($especialidade['codespecialidade'] ?? '') ?>"><?= h($especialidade['nome'] ?? '') ?></option>
<?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-medico-telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone" id="edit-medico-telefone">
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
        document.querySelectorAll('.js-edit-medico').forEach(function (button) {
            button.addEventListener('click', function () {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-medico-codigo').value = record.codmedico || '';
                document.getElementById('edit-medico-nome').value = record.nome || '';
                document.getElementById('edit-medico-crm').value = record.crm || '';
                document.getElementById('edit-medico-telefone').value = record.telefone || '';
                document.getElementById('edit-medico-especialidade').value = record.especialidade?.codespecialidade || '';
            });
        });
    </script>
<?php render_scripts(); ?>
