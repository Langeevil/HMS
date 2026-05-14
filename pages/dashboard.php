<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$medicosResponse = fetchResourceList('medicos');
$pacientesResponse = fetchResourceList('pacientes');
$leitosResponse = fetchResourceList('leitos');
$alasResponse = fetchResourceList('alas');
$quartosResponse = fetchResourceList('quartos');
$totais = [
    'medicos' => count(is_array($medicosResponse['data'] ?? null) ? $medicosResponse['data'] : []),
    'pacientes' => count(is_array($pacientesResponse['data'] ?? null) ? $pacientesResponse['data'] : []),
    'leitos' => count(is_array($leitosResponse['data'] ?? null) ? $leitosResponse['data'] : []),
    'alas' => count(is_array($alasResponse['data'] ?? null) ? $alasResponse['data'] : []),
    'quartos' => count(is_array($quartosResponse['data'] ?? null) ? $quartosResponse['data'] : []),
];

render_head('HMS - Dashboard', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell">
        <div class="row g-0">
<?php render_admin_sidebar($basePath, 'dashboard'); ?>
            <main class="col-lg-9 col-xl-10 content-area content-shell">
                <header class="top-header">
                    <div>
                        <p class="h5 mb-1 fw-bold">Resumo operacional</p>
                        <p class="mb-0 text-muted">Totais atuais dos principais cadastros.</p>
                    </div>
<?php render_user_menu(current_user_name(), url($basePath, 'pages/logout.php')); ?>
                </header>
                <section class="page-card mb-3">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <span class="eyebrow eyebrow-admin text-dark border-0">Painel da unidade</span>
                            <h1 class="display-6 fw-bold mt-3 mb-2">Situação geral dos cadastros</h1>
                            <p class="text-muted mb-0">Volumes registrados por área e atalhos para rotinas frequentes.</p>
                        </div>
                        <div class="col-lg-4">
                            <div class="metric-card">
                                <p class="text-muted mb-2">Indicadores</p>
                                <h2 class="h3 fw-bold mb-1">Cadastros ativos</h2>
                                <p class="text-muted mb-0">Números carregados diretamente da API.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="row g-3">
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/medicos/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-primary bg-opacity-10 text-primary mx-auto mb-3"><i class="bi bi-person-badge display-6"></i></div><h3 class="h4 fw-bold text-dark mb-1">Médicos</h3><p class="text-muted small mb-0"><?= h(($totais['medicos'] ?? 0) . ' profissionais') ?></p><div class="mt-3 text-primary fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/pacientes/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-success bg-opacity-10 text-success mx-auto mb-3"><i class="bi bi-people display-6"></i></div><h3 class="h4 fw-bold text-dark mb-1">Pacientes</h3><p class="text-muted small mb-0"><?= h(($totais['pacientes'] ?? 0) . ' registrados') ?></p><div class="mt-3 text-success fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-info bg-opacity-10 text-info mx-auto mb-3"><i class="bi bi-hospital display-6"></i></div><h3 class="h4 fw-bold text-dark mb-1">Leitos</h3><p class="text-muted small mb-0"><?= h(($totais['leitos'] ?? 0) . ' unidades') ?></p><div class="mt-3 text-info fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/alas/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-warning bg-opacity-10 text-warning mx-auto mb-3"><i class="bi bi-building display-6"></i></div><h3 class="h4 fw-bold text-dark mb-1">Alas</h3><p class="text-muted small mb-0"><?= h(($totais['alas'] ?? 0) . ' setores') ?></p><div class="mt-3 text-warning fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/quartos/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-danger bg-opacity-10 text-danger mx-auto mb-3"><i class="bi bi-door-open display-6"></i></div><h3 class="h4 fw-bold text-dark mb-1">Quartos</h3><p class="text-muted small mb-0"><?= h(($totais['quartos'] ?? 0) . ' unidades') ?></p><div class="mt-3 text-danger fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                </div>
                <div class="row mt-3 g-3">
                    <div class="col-lg-8"><div class="page-card h-100"><h2 class="h5 fw-bold mb-4">Atividade recente</h2><div class="text-center py-5 text-muted"><i class="bi bi-clock-history display-4 mb-3 d-block"></i><p class="mb-0">Ainda não há registros recentes para exibir.</p></div></div></div>
                    <div class="col-lg-4"><div class="page-card h-100"><h2 class="h5 fw-bold mb-4">Ações rápidas</h2><div class="d-grid gap-2"><a href="<?= h(url($basePath, 'pages/pacientes/form.php')) ?>" class="btn btn-outline-primary text-start"><i class="bi bi-plus-circle me-2"></i>Cadastrar paciente</a><a href="<?= h(url($basePath, 'pages/medicos/form.php')) ?>" class="btn btn-outline-primary text-start"><i class="bi bi-person-badge me-2"></i>Registrar médico</a><a href="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="btn btn-outline-primary text-start"><i class="bi bi-hospital me-2"></i>Consultar leitos</a></div></div></div>
                </div>
            </main>
        </div>
    </div>
<?php render_scripts(); ?>
