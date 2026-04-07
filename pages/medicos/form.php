<?php
declare(strict_types=1);
$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$medico = $medico ?? [];
$erro = null;
$formAction = url($basePath, 'pages/medicos/form.php');
$especialidadesResponse = fetchResourceList('especialidades');
$especialidades = is_array($especialidadesResponse['data'] ?? null) ? $especialidadesResponse['data'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medico = $_POST;
    $response = saveResource('medicos', $_POST);

    if ($response['success']) {
        set_flash('success', 'Medico salvo com sucesso.');
        header('Location: ' . url($basePath, 'pages/medicos/listar.php'));
        exit;
    }

    $erro = $response['error'] ?: 'Nao foi possivel salvar o medico.';
}

render_head('HMS - Novo Medico', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, 'medicos'); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1">Novo medico</h1><p class="text-muted mb-0">Cadastro com campos mais legiveis e acabamento visual consistente.</p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?></div></div>
            <div class="page-card"><form action="<?= h($formAction) ?>" method="post">
<?php if ($erro): ?>
                <div class="alert alert-danger mb-3"><?= h($erro) ?></div>
<?php endif; ?>
                <input type="hidden" name="codmedico" value="<?= h($medico['codmedico'] ?? '') ?>">
                <div class="row g-3">
                    <div class="col-md-6"><label for="nome" class="form-label">Nome</label><input type="text" name="nome" value="<?= h($medico['nome'] ?? '') ?>" class="form-control" id="nome" required></div>
                    <div class="col-md-6"><label for="crm" class="form-label">CRM</label><input type="text" name="crm" value="<?= h($medico['crm'] ?? '') ?>" class="form-control" id="crm" required></div>
                    <div class="col-md-6"><label for="codespecialidade" class="form-label">Especialidade</label><select name="codespecialidade" class="form-select" id="codespecialidade" required><option value="">Selecione...</option>
<?php foreach ($especialidades as $especialidade): ?>
                        <option value="<?= h($especialidade['codespecialidade'] ?? '') ?>"<?= selected_attr($medico['codespecialidade'] ?? ($medico['especialidade']['codespecialidade'] ?? ''), $especialidade['codespecialidade'] ?? '') ?>><?= h($especialidade['nome'] ?? '') ?></option>
<?php endforeach; ?>
                    </select></div>
                    <div class="col-md-6"><label for="telefone" class="form-label">Telefone</label><input type="text" name="telefone" value="<?= h($medico['telefone'] ?? '') ?>" class="form-control" id="telefone"></div>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'pages/medicos/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
