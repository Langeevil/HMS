<?php
declare(strict_types=1);
$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$pacientes = $pacientes ?? [];
require_once __DIR__ . '/../includes/app.php';
render_head('HMS - Pacientes', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'pacientes'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Pacientes</h1><p class="text-muted mb-0">Leitura mais clara para dados administrativos e de identificacao.</p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pacientes/form.php')) ?>" class="btn btn-primary">Novo paciente</a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr><th>CPF</th><th>Nome</th><th>Data nascimento</th><th>Tipo sanguineo</th><th class="text-end">Acoes</th></tr></thead><tbody>
<?php if ($pacientes === []): ?><?php empty_table_row(5, 'Nenhum paciente carregado. Conecte esta tela ao retorno da API Java.'); ?><?php else: foreach ($pacientes as $paciente): ?>
                <tr><td><?= h($paciente['cpf'] ?? '-') ?></td><td><?= h($paciente['nome'] ?? '-') ?></td><td><?= h($paciente['dataNascimentoFormatada'] ?? $paciente['dataNascimento'] ?? '-') ?></td><td><?= h(trim(($paciente['tipoSanguineo']['tipo'] ?? '') . ' ' . ($paciente['tipoSanguineo']['fatorrh'] ?? '')) ?: '-') ?></td><td class="text-end"><a href="<?= h($paciente['editar_url'] ?? '#') ?>" class="btn btn-sm btn-warning">Editar</a> <a href="<?= h($paciente['excluir_url'] ?? '#') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
