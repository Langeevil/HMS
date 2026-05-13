<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();

render_head('HMS - Perfil do Usuário', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell">
        <div class="row g-0">
<?php render_admin_sidebar($basePath, ''); ?>
            <main class="col-lg-9 col-xl-10 content-area content-shell">
                <header class="top-header">
                    <div>
                        <h5 class="mb-1 fw-bold">Meu Perfil</h5>
                        <p class="mb-0 text-muted">Visualize e gerencie suas informações de perfil.</p>
                    </div>
<?php render_user_menu(current_user_name(), url($basePath, 'pages/logout.php')); ?>
                </header>

                <div class="page-card mb-3">
                    <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Informações Pessoais</h6>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div style="width: 120px; height: 120px; border-radius: 20px; background: linear-gradient(135deg, rgba(87, 183, 200, 0.2), rgba(45, 156, 143, 0.2)); display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 2px solid rgba(18, 48, 68, 0.12);">
                                    <i class="bi bi-person-fill" style="font-size: 3.5rem; color: var(--hms-primary);"></i>
                                </div>
                                <h5 class="fw-bold mt-3 mb-1"><?= h(current_user_name()) ?></h5>
                                <p class="text-muted small mb-0">Administrador</p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div>
                                        <label class="form-label fw-bold small text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em; color: var(--hms-text-soft);">Nome Completo</label>
                                        <p class="form-control border-0 bg-light text-muted mb-0"><?= h($usuarioLogado['nome'] ?? current_user_name()) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <label class="form-label fw-bold small text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em; color: var(--hms-text-soft);">Email</label>
                                        <p class="form-control border-0 bg-light text-muted mb-0"><?= h($usuarioLogado['email'] ?? 'não informado') ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <label class="form-label fw-bold small text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em; color: var(--hms-text-soft);">Função</label>
                                        <p class="form-control border-0 bg-light text-muted mb-0">Administrador</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <label class="form-label fw-bold small text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em; color: var(--hms-text-soft);">Status</label>
                                        <div class="mb-0">
                                            <span class="badge bg-success">Ativo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-card mb-3">
                    <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Segurança</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em; color: var(--hms-text-soft);">Última autenticação</label>
                            <p class="form-control border-0 bg-light text-muted mb-0">Sessão ativa</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em; color: var(--hms-text-soft);">Endereço IP</label>
                            <p class="form-control border-0 bg-light text-muted mb-0"><?= h($_SERVER['REMOTE_ADDR'] ?? 'desconhecido') ?></p>
                        </div>
                    </div>
                </div>

                <div class="page-card">
                    <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Ações</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" disabled><i class="bi bi-pencil me-2"></i>Editar Perfil</button>
                        <button type="button" class="btn btn-outline-primary" disabled><i class="bi bi-key me-2"></i>Alterar Senha</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php render_scripts(); ?>
