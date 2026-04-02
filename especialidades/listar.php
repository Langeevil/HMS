<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$especialidades = $especialidades ?? [];
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Especialidades', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'especialidades'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Especialidades</h1><p class="text-muted mb-0">Cadastro de areas clinicas em um layout mais atual e consistente.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'especialidades/form.php')) ?>" class="btn btn-primary">Nova especialidade</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>Nome</th><th>Descricao</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($especialidades === []): ?><?php empty_table_row(3, 'Nenhuma especialidade carregada. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($especialidades as $especialidade): ?>
                <tr><td><?= h($especialidade['nome'] ?? '-') ?></td><td><?= h($especialidade['descricao'] ?? '-') ?></td><td class="text-end"><a href="<?= h($especialidade['editar_url'] ?? '#') ?>" class="btn btn-sm btn-warning">Editar</a> <a href="<?= h($especialidade['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
