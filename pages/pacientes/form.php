<?php
declare(strict_types=1);
$basePath = '../..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$paciente = $paciente ?? [];
$tiposSanguineos = $tiposSanguineos ?? [];
$formAction = $formAction ?? '#';
require_once __DIR__ . '/../../includes/app.php';
render_head('HMS - Novo Paciente', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'pacientes'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Novo paciente</h1><p class="text-muted mb-0">Formulario com aparencia mais contemporanea e foco em leitura.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
                <input type="hidden" name="codpaciente" value="<?= h($paciente['codpaciente'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-6"><label for="nome" class="form-label">Nome completo</label><input type="text" name="nome" value="<?= h($paciente['nome'] ?? '') ?>" class="form-control" id="nome" required></div>
                    <div class="col-md-6"><label for="cpf" class="form-label">CPF</label><input type="text" name="cpf" value="<?= h($paciente['cpf'] ?? '') ?>" class="form-control" id="cpf" required></div>
                    <div class="col-md-6"><label for="dataNascimento" class="form-label">Data de nascimento</label><input type="date" name="dataNascimento" value="<?= h($paciente['dataNascimento'] ?? '') ?>" class="form-control" id="dataNascimento" required></div>
                    <div class="col-md-6"><label for="tipoSanguineo" class="form-label">Tipo sanguineo</label><select name="tipoSanguineo" class="form-select" id="tipoSanguineo" required><option value="">Selecione...</option>
<?php foreach ($tiposSanguineos as $tipo): ?>
                        <option value="<?= h($tipo['codtipo'] ?? '') ?>"<?= selected_attr($paciente['tipoSanguineo']['codtipo'] ?? '', $tipo['codtipo'] ?? '') ?>><?= h(trim(($tipo['tipo'] ?? '') . ' ' . ($tipo['fatorrh'] ?? ''))) ?></option>
<?php endforeach; ?>
                    </select></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'pages/pacientes/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
