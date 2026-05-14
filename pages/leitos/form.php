<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$leito = $leito ?? [];
$erro = null;
$formAction = url($basePath, 'pages/leitos/form.php');
$quartosResponse = fetchResourceList('quartos');
$quartos = is_array($quartosResponse['data'] ?? null) ? $quartosResponse['data'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leito = $_POST;
    if (($leito['status'] ?? '') === '') {
        $leito['status'] = 'livre';
        $_POST['status'] = 'livre';
    }

    if (($_POST['status'] ?? '') === 'ocupado') {
        $response = ['success' => false, 'error' => 'Crie o leito como livre ou em manutenção. Para ocupar, use a ação Ocupar na listagem.'];
    } else {
        $response = saveResource('leitos', $_POST);
    }

    if ($response['success']) {
        set_flash('success', 'Leito salvo com sucesso.');
        header('Location: ' . url($basePath, 'pages/leitos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Não foi possível salvar o leito.';
}

render_head('HMS - Novo Leito', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'leitos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Novo leito</h1><p class="text-muted mb-0">Informe status inicial e quarto do leito.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuário', url($basePath, 'pages/logout.php')); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
<?php if ($erro): ?>
                <div class="alert alert-danger mb-3" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
                <input type="hidden" name="codleito" value="<?= h($leito['codleito'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-6"><label for="codquarto" class="form-label">Quarto</label><select name="codquarto" class="form-select" id="codquarto" required><option value="">Selecione...</option>
<?php foreach ($quartos as $quarto): ?>
                        <option value="<?= h($quarto['codquarto'] ?? '') ?>"<?= selected_attr($leito['codquarto'] ?? ($leito['quarto']['codquarto'] ?? ''), $quarto['codquarto'] ?? '') ?>><?= h(($quarto['numero'] ?? '-') . ' - ' . ($quarto['ala']['nome'] ?? '-')) ?></option>
<?php endforeach; ?>
                    </select></div>
                    <div class="col-md-6"><label for="status" class="form-label">Status</label><select name="status" class="form-select" id="status" required>
                        <option value="livre"<?= selected_attr($leito['status'] ?? 'livre', 'livre') ?>>Livre</option>
                        <option value="manutencao"<?= selected_attr($leito['status'] ?? '', 'manutencao') ?>>Manutenção</option>
                    </select></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
