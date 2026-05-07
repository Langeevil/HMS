<?php

declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;

$statusBadgeClasses = [
    'livre' => 'bg-success',
    'ocupado' => 'bg-danger',
    'manutencao' => 'bg-warning text-dark',
];

$statusLabels = [
    'livre' => 'Livre',
    'ocupado' => 'Ocupado',
    'manutencao' => 'Manutenção',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    $codleito = $_POST['codleito'] ?? '';

    if ($formAction === 'edit_leito') {
        if ($codleito === '') {
            $response = ['success' => false, 'error' => 'Código do leito nao informado.'];
        } elseif (($_POST['status'] ?? '') === 'ocupado') {
            $response = ['success' => false, 'error' => 'Use a ação Ocupar para alterar o status do leito para ocupado.'];
        } else {
            $response = updateResource('leitos', $codleito, $_POST);
        }

        if ($response['success']) {
            set_flash('success', 'Leito atualizado com sucesso.');
            header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Não foi possível atualizar o leito.';
    }

    if ($formAction === 'ocupar_leito') {
        $response = $codleito !== ''
            ? occupyLeito($codleito)
            : ['success' => false, 'error' => 'Código do leito nao informado.'];

        if ($response['success']) {
            set_flash('success', 'Leito ocupado com sucesso.');
            header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Não foi possível ocupar o leito.';
    }

    if ($formAction === 'liberar_leito') {
        $response = $codleito !== ''
            ? releaseLeito($codleito)
            : ['success' => false, 'error' => 'Código do leito nao informado.'];

        if ($response['success']) {
            set_flash('success', 'Leito liberado com sucesso.');
            header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Não foi possível liberar o leito.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $response = deleteResource('leitos', $_GET['delete_id']);

    if ($response['success']) {
        set_flash('success', 'Leito excluido com sucesso.');
        header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Não foi possível excluir o leito.';
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
    <div class="container-fluid admin-shell">
        <div class="row g-0">
            <?php render_admin_sidebar($basePath, 'leitos'); ?>
            <main class="col-lg-9 col-xl-10 content">
                <div class="page-toolbar">
                    <div>
                        <h1 class="h2 fw-bold mb-1">Leitos</h1>
                        <p class="text-muted mb-0">Monitoramento administrativo com acabamento mais contemporaneo.</p>
                    </div>
                    <div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/leitos/form.php')) ?>" class="btn btn-primary">Novo leito</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuário', url($basePath, 'pages/logout.php')); ?></div>
                </div>
                <?php if ($flash): ?>
                    <div class="alert alert-success" role="status"><?= h($flash) ?></div>
                <?php endif; ?>
                <?php if ($erro): ?>
                    <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
                <?php endif; ?>
                <div class="page-card table-shell">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Número</th>
                                <th scope="col">Quarto</th>
                                <th scope="col">Ala</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($leitos === []): ?><?php empty_table_row(6, 'Nenhum leito carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($leitos as $leito): ?>
                            <?php
                                $status = strtolower((string) ($leito['status'] ?? 'livre'));
                                $statusClass = $statusBadgeClasses[$status] ?? 'bg-secondary';
                                $statusLabel = $statusLabels[$status] ?? ($leito['status'] ?? 'Livre');
                            ?>
                            <tr>
                                <td><?= h($leito['codleito'] ?? '-') ?></td>
                                <td><?= h($leito['numero'] ?? '-') ?></td>
                                <td><?= h($leito['quarto']['numero'] ?? ($leito['codquarto'] ?? '-')) ?></td>
                                <td><?= h($leito['quarto']['ala']['nome'] ?? ($leito['codala'] ?? '-')) ?></td>
                                <td><span class="badge <?= h($statusClass) ?>"><?= h($statusLabel) ?></span></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1 flex-wrap justify-content-end">
                                        <?php if ($status === 'ocupado'): ?>
                                            <button type="button" class="btn btn-sm btn-warning" disabled title="Libere o leito antes de editar seus dados cadastrais.">Editar</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-warning js-edit-leito" data-record="<?= json_attr($leito) ?>" data-bs-toggle="modal" data-bs-target="#editLeitoModal">Editar</button>
                                        <?php endif; ?>
                                        <?php if ($status === 'livre'): ?>
                                            <form method="post" action="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="d-inline">
                                                <input type="hidden" name="form_action" value="ocupar_leito">
                                                <input type="hidden" name="codleito" value="<?= h($leito['codleito'] ?? '') ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirmar ocupação deste leito?')">Ocupar</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if ($status === 'ocupado'): ?>
                                            <form method="post" action="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="d-inline">
                                                <input type="hidden" name="form_action" value="liberar_leito">
                                                <input type="hidden" name="codleito" value="<?= h($leito['codleito'] ?? '') ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Confirmar liberação deste leito?')">Liberar</button>
                                            </form>
                                        <?php endif; ?>
                                        <a href="<?= h($leito['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                    <?php endforeach;
                                                                                                                                                        endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

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
                            <label for="edit-leito-numero" class="form-label">Número do leito</label>
                            <input type="number" class="form-control" name="numero" id="edit-leito-numero" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-leito-status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit-leito-status" required>
                                <option value="livre">Livre</option>
                                <option value="manutencao">Manutenção</option>
                            </select>
                            <div class="form-text">Para ocupar ou liberar um leito, use as ações da tabela.</div>
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
                        <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.js-edit-leito').forEach(function(button) {
            button.addEventListener('click', function() {
                const record = JSON.parse(button.dataset.record || '{}');
                document.getElementById('edit-leito-codigo').value = record.codleito || '';
                document.getElementById('edit-leito-numero').value = record.numero || '';
                document.getElementById('edit-leito-status').value = ['livre', 'manutencao'].includes(record.status) ? record.status : 'livre';
                document.getElementById('edit-leito-codquarto').value = record.codquarto || record.quarto?.codquarto || '';
            });
        });
    </script>
    <?php render_scripts(); ?>
