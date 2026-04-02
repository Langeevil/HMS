<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$especialidade = $especialidade ?? [];
$formAction = $formAction ?? '#';
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Nova Especialidade', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'especialidades'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Nova especialidade</h1><p class="text-muted mb-0">Campos mantidos, com visual refinado para uso administrativo.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
                <input type="hidden" name="codespecialidade" value="<?= h($especialidade['codespecialidade'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-6"><label for="nome" class="form-label">Nome da especialidade</label><input type="text" name="nome" value="<?= h($especialidade['nome'] ?? '') ?>" class="form-control" id="nome" required></div>
                    <div class="col-md-6"><label for="descricao" class="form-label">Descricao</label><textarea name="descricao" class="form-control" id="descricao" rows="3"><?= h($especialidade['descricao'] ?? '') ?></textarea></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'especialidades/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
