<?php
declare(strict_types=1);

$basePath = '.';
require_once __DIR__ . '/includes/app.php';

render_head('Hospital Management System - HMS', $basePath);
?>
<body id="inicio" class="public-page">
    <a class="skip-link" href="#conteudo-principal">Pular para conteudo principal</a>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#inicio">
                <img src="<?= h(url($basePath, 'assets/images/HMS.png')) ?>" alt="Logo HMS" class="brand-logo-image">
                <span>
                    <span class="d-block">HMS</span>
                    <small class="d-block text-white-50 fw-normal">Hospital Management System</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menu de navegacao">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link active" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sobre">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="#planos">Planos</a></li>
                    <li class="nav-item ms-lg-3"><a href="<?= h(url($basePath, 'pages/login.php')) ?>" class="btn btn-primary px-4">Entrar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main id="conteudo-principal">
        <header class="hero-section">
            <div class="container">
                <div class="hero-panel">
                    <span class="eyebrow">Gestao hospitalar inteligente</span>
                    <h1 class="hero-title fw-bold">Gestao Hospitalar Inteligente</h1>
                    <p class="hero-copy mb-0">A solucao definitiva para administrar seu hospital com eficiencia e seguranca.</p>
                    <div class="hero-actions">
                        <a href="<?= h(url($basePath, 'pages/cadastro.php')) ?>" class="btn btn-light btn-lg px-5">Comecar Agora</a>
                        <a href="#sobre" class="btn btn-outline-light btn-lg px-5">Saiba Mais</a>
                    </div>
                </div>
            </div>
        </header>

        <section id="sobre" class="section-padding">
            <div class="container text-center">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <h2 class="display-5 fw-bold mb-3">Sobre o HMS</h2>
                        <p class="lead text-muted">O Hospital Management System (HMS) e uma plataforma integrada desenvolvida para otimizar todos os processos hospitalares, desde o cadastro de pacientes ate a gestao de leitos e especialidades medicas.</p>
                    </div>
                </div>
                <div class="row g-4 text-start">
                    <div class="col-md-4"><div class="feature-card"><h3 class="h4 fw-bold text-primary">Gestao de Pacientes</h3><p class="text-muted mb-0">Controle total sobre o historico de prontuarios, triagem e acompanhamento detalhado.</p></div></div>
                    <div class="col-md-4"><div class="feature-card"><h3 class="h4 fw-bold text-primary">Corpo Medico</h3><p class="text-muted mb-0">Gerenciamento de escalas, especialidades e disponibilidade de profissionais em tempo real.</p></div></div>
                    <div class="col-md-4"><div class="feature-card"><h3 class="h4 fw-bold text-primary">Controle de Leitos</h3><p class="text-muted mb-0">Monitoramento visual e em tempo real da ocupacao de alas, quartos e leitos hospitalares.</p></div></div>
                </div>
            </div>
        </section>

        <section id="planos" class="section-padding section-muted">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold">Nossos Planos</h2>
                    <p class="text-muted">Escolha a solucao que melhor se adapta a sua unidade de saude.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-4"><div class="plan-card text-center"><h3 class="h4 fw-bold">Clinica</h3><h4 class="display-6 fw-bold text-primary mb-3">R$ 199<small class="text-muted fs-6">/mes</small></h4><ul class="list-unstyled text-start text-muted mb-4"><li class="mb-2">Ate 5 usuarios</li><li class="mb-2">Gestao de Pacientes</li><li class="mb-2">Suporte via Email</li></ul><a href="<?= h(url($basePath, 'pages/cadastro.php?plano=clinica')) ?>" class="btn btn-outline-primary">Selecionar</a></div></div>
                    <div class="col-md-4"><div class="plan-card featured text-center"><h3 class="h4 fw-bold">Hospital</h3><h4 class="display-6 fw-bold text-primary mb-3">R$ 499<small class="text-muted fs-6">/mes</small></h4><ul class="list-unstyled text-start text-muted mb-4"><li class="mb-2">Usuarios ilimitados</li><li class="mb-2">Gestao de Leitos</li><li class="mb-2">Gestao de Medicos</li><li class="mb-2">Suporte 24/7</li></ul><a href="<?= h(url($basePath, 'pages/cadastro.php?plano=hospital')) ?>" class="btn btn-primary">Selecionar</a></div></div>
                    <div class="col-md-4"><div class="plan-card text-center"><h3 class="h4 fw-bold">Enterprise</h3><h4 class="display-6 fw-bold text-primary mb-3">Custom</h4><ul class="list-unstyled text-start text-muted mb-4"><li class="mb-2">Multiplas Unidades</li><li class="mb-2">API de Integracao</li><li class="mb-2">Gerente de conta</li></ul><a href="<?= h(url($basePath, 'pages/cadastro.php?plano=enterprise')) ?>" class="btn btn-outline-primary">Fale Conosco</a></div></div>
                </div>
            </div>
        </section>
    </main>

<?php render_footer(); ?>
<?php render_scripts(); ?>
