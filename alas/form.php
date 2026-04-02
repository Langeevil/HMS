<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$ala = $ala ?? [];
$formAction = $formAction ?? '#';
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Nova Ala', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'alas'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Nova ala</h1><p class="text-muted mb-0">Formulario remodelado mantendo a mesma hierarquia do conteudo.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
                <input type="hidden" name="codala" value="<?= h($ala['codala'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-8"><label for="nome" class="form-label">Nome da ala</label><input type="text" name="nome" value="<?= h($ala['nome'] ?? '') ?>" class="form-control" id="nome" required></div>
                    <div class="col-md-4"><label for="andar" class="form-label">Andar</label><input type="number" name="andar" value="<?= h($ala['andar'] ?? '') ?>" class="form-control" id="andar"></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'alas/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
