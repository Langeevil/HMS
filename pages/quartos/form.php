<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$quarto = $quarto ?? [];
$erro = null;
$formAction = url($basePath, 'pages/quartos/form.php');
$alasResponse = fetchResourceList('alas');
$alas = is_array($alasResponse['data'] ?? null) ? $alasResponse['data'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quarto = $_POST;
    $response = saveResource('quartos', $_POST);

    if ($response['success']) {
        set_flash('success', 'Quarto salvo com sucesso.');
        header('Location: ' . url($basePath, 'pages/quartos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Não foi possível salvar o quarto.';
}

render_head('HMS - Novo Quarto', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'quartos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Novo quarto</h1><p class="text-muted mb-0">Formulário em superfície mais leve, mantendo a mesma sequência de campos.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuário', url($basePath, 'pages/logout.php')); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
<?php if ($erro): ?>
                <div class="alert alert-danger mb-3" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
                <input type="hidden" name="codquarto" value="<?= h($quarto['codquarto'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-4"><label for="numero" class="form-label">Número do quarto</label><input type="number" name="numero" value="<?= h($quarto['numero'] ?? '') ?>" class="form-control" id="numero" required></div>
                    <div class="col-md-4"><label for="tipo" class="form-label">Tipo de quarto</label><input type="text" name="tipo" value="<?= h($quarto['tipo'] ?? '') ?>" class="form-control" id="tipo" placeholder="Ex: Particular"></div>
                    <div class="col-md-4"><label for="codala" class="form-label">Ala</label><select name="codala" class="form-select" id="codala" required><option value="">Selecione...</option>
<?php foreach ($alas as $ala): ?>
                        <option value="<?= h($ala['codala'] ?? '') ?>"<?= selected_attr($quarto['codala'] ?? ($quarto['ala']['codala'] ?? ''), $ala['codala'] ?? '') ?>><?= h($ala['nome'] ?? '') ?></option>
<?php endforeach; ?>
                    </select></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'pages/quartos/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
