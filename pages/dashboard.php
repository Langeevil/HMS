<?php
declare(strict_types=1);

$basePath = '..';
$usuarioLogado = $usuarioLogado ?? ['nome' => 'Usuario'];
$totais = $totais ?? ['medicos' => 0, 'pacientes' => 0, 'leitos' => 0, 'alas' => 0, 'quartos' => 0];
require_once __DIR__ . '/../includes/app.php';

render_head('HMS - Dashboard', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell">
        <div class="row g-0">
<?php render_admin_sidebar($basePath, 'dashboard'); ?>
            <main class="col-lg-9 col-xl-10 content-area content-shell">
                <header class="top-header">
                    <div>
                        <h5 class="mb-1 fw-bold">Resumo do sistema</h5>
                        <p class="mb-0 text-muted">Acompanhe os principais modulos administrativos e a rotina da unidade em um unico painel.</p>
                    </div>
<?php render_user_menu($usuarioLogado['nome'] ?? 'Usuario'); ?>
                </header>
                <section class="page-card mb-3">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <span class="eyebrow text-dark border-0" style="background: rgba(87, 183, 200, 0.12); color: var(--hms-primary);">Central de operacoes</span>
                            <h1 class="display-6 fw-bold mt-3 mb-2">Gestao integrada do ambiente hospitalar.</h1>
                            <p class="text-muted mb-0">Consulte equipes medicas, pacientes, leitos, alas, quartos e especialidades com acesso rapido aos principais fluxos administrativos.</p>
                        </div>
                        <div class="col-lg-4">
                            <div class="metric-card">
                                <p class="text-muted mb-2">Visao operacional</p>
                                <h3 class="fw-bold mb-1">Controle rapido dos setores</h3>
                                <p class="text-muted mb-0">Acesse os cadastros essenciais para manter a unidade organizada e acompanhar a ocupacao hospitalar.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="row g-3">
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/medicos/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-primary bg-opacity-10 text-primary mx-auto mb-3"><i class="bi bi-person-badge display-6"></i></div><h4 class="fw-bold text-dark mb-1">Medicos</h4><p class="text-muted small mb-0"><?= h(($totais['medicos'] ?? 0) . ' profissionais') ?></p><div class="mt-3 text-primary fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/pacientes/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-success bg-opacity-10 text-success mx-auto mb-3"><i class="bi bi-people display-6"></i></div><h4 class="fw-bold text-dark mb-1">Pacientes</h4><p class="text-muted small mb-0"><?= h(($totais['pacientes'] ?? 0) . ' registrados') ?></p><div class="mt-3 text-success fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-info bg-opacity-10 text-info mx-auto mb-3"><i class="bi bi-hospital display-6"></i></div><h4 class="fw-bold text-dark mb-1">Leitos</h4><p class="text-muted small mb-0"><?= h(($totais['leitos'] ?? 0) . ' unidades') ?></p><div class="mt-3 text-info fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/alas/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-warning bg-opacity-10 text-warning mx-auto mb-3"><i class="bi bi-building display-6"></i></div><h4 class="fw-bold text-dark mb-1">Alas</h4><p class="text-muted small mb-0"><?= h(($totais['alas'] ?? 0) . ' setores') ?></p><div class="mt-3 text-warning fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                    <div class="col-md-6 col-xl-4"><a href="<?= h(url($basePath, 'pages/quartos/listar.php')) ?>" class="text-decoration-none"><div class="card stat-card h-100 text-center p-4 border-0"><div class="icon-shape bg-danger bg-opacity-10 text-danger mx-auto mb-3"><i class="bi bi-door-open display-6"></i></div><h4 class="fw-bold text-dark mb-1">Quartos</h4><p class="text-muted small mb-0"><?= h(($totais['quartos'] ?? 0) . ' unidades') ?></p><div class="mt-3 text-danger fw-bold small">Gerenciar <i class="bi bi-arrow-right"></i></div></div></a></div>
                </div>
                <div class="row mt-3 g-3">
                    <div class="col-lg-8"><div class="page-card h-100"><h5 class="fw-bold mb-4">Atividade recente</h5><div class="text-center py-5 text-muted"><i class="bi bi-clock-history display-4 mb-3 d-block"></i><p class="mb-0">Novos cadastros, atualizacoes e movimentacoes do sistema podem ser acompanhados aqui.</p></div></div></div>
                    <div class="col-lg-4"><div class="page-card h-100"><h5 class="fw-bold mb-4">Acesso rapido</h5><div class="d-grid gap-2"><a href="<?= h(url($basePath, 'pages/pacientes/form.php')) ?>" class="btn btn-outline-primary text-start"><i class="bi bi-plus-circle me-2"></i>Cadastrar paciente</a><a href="<?= h(url($basePath, 'pages/medicos/form.php')) ?>" class="btn btn-outline-primary text-start"><i class="bi bi-person-badge me-2"></i>Registrar medico</a><a href="<?= h(url($basePath, 'pages/leitos/listar.php')) ?>" class="btn btn-outline-primary text-start"><i class="bi bi-hospital me-2"></i>Consultar leitos</a></div></div></div>
                </div>
            </main>
        </div>
    </div>
<?php render_scripts(); ?>
