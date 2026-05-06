<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_paciente') {
    $response = saveResource('pacientes', $_POST);

    if ($response['success']) {
        set_flash('success', 'Paciente atualizado com sucesso.');
        header('Location: ' . url($basePath, 'pages/pacientes/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel atualizar o paciente.';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $response = deleteResource('pacientes', $_GET['delete_id']);

    if ($response['success']) {
        set_flash('success', 'Paciente excluido com sucesso.');
        header('Location: ' . url($basePath, 'pages/pacientes/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel excluir o paciente.';
}

$tiposResponse = fetchResourceList('tipos-sanguineos');
$tiposSanguineos = is_array($tiposResponse['data'] ?? null) ? $tiposResponse['data'] : [];
$response = fetchResourceList('pacientes');
$pacientes = is_array($response['data'] ?? null) ? $response['data'] : [];
foreach ($pacientes as &$paciente) {
    $paciente['excluir_url'] = url($basePath, 'pages/pacientes/listar.php?delete_id=' . ($paciente['codpaciente'] ?? ''));
}
unset($paciente);
render_head('HMS - Pacientes', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'pacientes'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Pacientes</h1><p class="text-muted mb-0">Leitura mais clara para dados administrativos e de identificacao.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/pacientes/form.php')) ?>" class="btn btn-primary">Novo paciente</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>CPF</th><th>Nome</th><th>Data nascimento</th><th>Tipo sanguineo</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($pacientes === []): ?><?php empty_table_row(5, 'Nenhum paciente carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($pacientes as $paciente): ?>
                <tr><td><?= h($paciente['cpf'] ?? '-') ?></td><td><?= h($paciente['nome'] ?? '-') ?></td><td><?= h($paciente['dataNascimentoFormatada'] ?? $paciente['dataNascimento'] ?? '-') ?></td><td><?= h(trim(($paciente['tipoSanguineo']['tipo'] ?? '') . ' ' . ($paciente['tipoSanguineo']['fatorrh'] ?? '')) ?: '-') ?></td><td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-paciente" data-record="<?= json_attr($paciente) ?>" data-bs-toggle="modal" data-bs-target="#editPacienteModal">Editar</button> <a href="<?= h($paciente['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editPacienteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/pacientes/listar.php')) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_paciente">
                        <input type="hidden" name="codpaciente" id="edit-paciente-codigo">
                        <div class="mb-3">
                            <label for="edit-paciente-nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="edit-paciente-nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-paciente-cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="edit-paciente-cpf" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-paciente-data" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" name="dataNascimento" id="edit-paciente-data" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-paciente-codtipo" class="form-label">Tipo sanguineo</label>
                            <select class="form-select" name="codtipo" id="edit-paciente-codtipo" required>
                                <option value="">Selecione...</option>
<?php foreach ($tiposSanguineos as $tipo): ?>
                                <option value="<?= h($tipo['codtipo'] ?? '') ?>"><?= h(trim(($tipo['tipo'] ?? '') . ' ' . ($tipo['fatorrh'] ?? ''))) ?></option>
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
        document.querySelectorAll('.js-edit-paciente').forEach(function (button) {
            button.addEventListener('click', function () {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-paciente-codigo').value = record.codpaciente || '';
                document.getElementById('edit-paciente-nome').value = record.nome || '';
                document.getElementById('edit-paciente-cpf').value = record.cpf || '';
                document.getElementById('edit-paciente-data').value = record.dataNascimento || '';
                document.getElementById('edit-paciente-codtipo').value = record.tipoSanguineo?.codtipo || '';
            });
        });
    </script>
<?php render_scripts(); ?>
