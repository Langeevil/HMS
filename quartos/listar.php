<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$quartos = $quartos ?? [];
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Quartos', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'quartos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Quartos</h1><p class="text-muted mb-0">Tabela modernizada sem alterar a disposicao dos dados operacionais.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'quartos/form.php')) ?>" class="btn btn-primary">Novo quarto</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Numero</th><th>Tipo</th><th>Ala</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($quartos === []): ?><?php empty_table_row(4, 'Nenhum quarto carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($quartos as $quarto): ?>
                <tr><td><?= h($quarto['numero'] ?? '-') ?></td><td><?= h($quarto['tipo'] ?? '-') ?></td><td><?= h($quarto['ala']['nome'] ?? '-') ?></td><td class="text-end"><a href="<?= h($quarto['editar_url'] ?? '#') ?>" class="btn btn-sm btn-warning">Editar</a> <a href="<?= h($quarto['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
