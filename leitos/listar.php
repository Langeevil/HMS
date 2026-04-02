<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$leitos = $leitos ?? [];
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Leitos', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'leitos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Leitos</h1><p class="text-muted mb-0">Monitoramento administrativo com acabamento mais contemporaneo.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Numero</th><th>Quarto</th><th>Ala</th><th>Status</th></tr></thead><tbody>
<?php if ($leitos === []): ?><?php empty_table_row(4, 'Nenhum leito carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($leitos as $leito): ?>
                <tr><td><?= h($leito['numero'] ?? '-') ?></td><td><?= h($leito['quarto']['numero'] ?? '-') ?></td><td><?= h($leito['quarto']['ala']['nome'] ?? '-') ?></td><td><span class="badge bg-success"><?= h($leito['status'] ?? 'Disponivel') ?></span></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
