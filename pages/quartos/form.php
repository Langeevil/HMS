<?php
declare(strict_types=1);
$basePath = '../..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$quarto = $quarto ?? [];
$alas = $alas ?? [];
$formAction = $formAction ?? '#';
require_once __DIR__ . '/../../includes/app.php';
render_head('HMS - Novo Quarto', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'quartos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Novo quarto</h1><p class="text-muted mb-0">Formulario em superficie mais leve, mantendo a mesma sequencia de campos.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
                <input type="hidden" name="codquarto" value="<?= h($quarto['codquarto'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-4"><label for="numero" class="form-label">Numero do quarto</label><input type="number" name="numero" value="<?= h($quarto['numero'] ?? '') ?>" class="form-control" id="numero" required></div>
                    <div class="col-md-4"><label for="tipo" class="form-label">Tipo de quarto</label><input type="text" name="tipo" value="<?= h($quarto['tipo'] ?? '') ?>" class="form-control" id="tipo" placeholder="Ex: Particular"></div>
                    <div class="col-md-4"><label for="ala" class="form-label">Ala</label><select name="ala" class="form-select" id="ala" required><option value="">Selecione...</option>
<?php foreach ($alas as $ala): ?>
                        <option value="<?= h($ala['codala'] ?? '') ?>"<?= selected_attr($quarto['ala']['codala'] ?? '', $ala['codala'] ?? '') ?>><?= h($ala['nome'] ?? '') ?></option>
<?php endforeach; ?>
                    </select></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'pages/quartos/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
