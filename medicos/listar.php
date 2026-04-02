<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$medicos = $medicos ?? [];
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Medicos', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'medicos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Medicos</h1><p class="text-muted mb-0">Gerenciamento do corpo clinico com visual mais limpo e atual.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'medicos/form.php')) ?>" class="btn btn-primary">Novo medico</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>CRM</th><th>Nome</th><th>Especialidade</th><th>Telefone</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($medicos === []): ?><?php empty_table_row(5, 'Nenhum medico carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($medicos as $medico): ?>
                <tr><td><?= h($medico['crm'] ?? '-') ?></td><td><?= h($medico['nome'] ?? '-') ?></td><td><?= h($medico['especialidade']['nome'] ?? '-') ?></td><td><?= h($medico['telefone'] ?? '-') ?></td><td class="text-end"><a href="<?= h($medico['editar_url'] ?? '#') ?>" class="btn btn-sm btn-warning">Editar</a> <a href="<?= h($medico['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
