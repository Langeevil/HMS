<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$alas = $alas ?? [];
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Alas', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'alas'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Alas</h1><p class="text-muted mb-0">Organizacao setorial com identidade visual alinhada ao restante do sistema.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'alas/form.php')) ?>" class="btn btn-primary">Nova ala</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Nome</th><th>Andar</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($alas === []): ?><?php empty_table_row(3, 'Nenhuma ala carregada. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($alas as $ala): ?>
                <tr><td><?= h($ala['nome'] ?? '-') ?></td><td><?= h($ala['andar'] ?? '-') ?></td><td class="text-end"><a href="<?= h($ala['editar_url'] ?? '#') ?>" class="btn btn-sm btn-warning">Editar</a> <a href="<?= h($ala['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
