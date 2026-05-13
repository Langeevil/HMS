<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();

render_head('HMS - Configurações', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell">
        <div class="row g-0">
<?php render_admin_sidebar($basePath, ''); ?>
            <main class="col-lg-9 col-xl-10 content-area content-shell">
                <header class="top-header">
                    <div>
                        <h5 class="mb-1 fw-bold">Configurações</h5>
                        <p class="mb-0 text-muted">Personalize as opções e preferências do sistema.</p>
                    </div>
<?php render_user_menu(current_user_name(), url($basePath, 'pages/logout.php')); ?>
                </header>

                <div class="row g-3">
                    <div class="col-lg-3">
                        <div class="page-card sticky-top" style="top: 1rem;">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-sm btn-primary text-start active" data-bs-toggle="list" data-bs-target="#settings-general" disabled>
                                    <i class="bi bi-gear me-2"></i>Geral
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary text-start" data-bs-toggle="list" data-bs-target="#settings-appearance" disabled>
                                    <i class="bi bi-palette me-2"></i>Aparência
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary text-start" data-bs-toggle="list" data-bs-target="#settings-notifications" disabled>
                                    <i class="bi bi-bell me-2"></i>Notificações
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary text-start" data-bs-toggle="list" data-bs-target="#settings-security" disabled>
                                    <i class="bi bi-shield-check me-2"></i>Segurança
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary text-start" data-bs-toggle="list" data-bs-target="#settings-privacy" disabled>
                                    <i class="bi bi-eye-slash me-2"></i>Privacidade
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <!-- Configurações Gerais -->
                        <div class="page-card mb-3" id="settings-general">
                            <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Configurações Gerais</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Idioma</label>
                                    <select class="form-select" disabled>
                                        <option selected>Português (Brasil)</option>
                                        <option>Português (Portugal)</option>
                                        <option>English</option>
                                        <option>Español</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Fuso horário</label>
                                    <select class="form-select" disabled>
                                        <option selected>Horário de Brasília (UTC-3)</option>
                                        <option>Horário de Manaus (UTC-4)</option>
                                        <option>Horário de Fernando de Noronha (UTC-2)</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="enabled-notifications" disabled>
                                        <label class="form-check-label fw-bold small" for="enabled-notifications">Ativar notificações do sistema</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="enabled-analytics" disabled>
                                        <label class="form-check-label fw-bold small" for="enabled-analytics">Compartilhar dados de uso com HMS</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Aparência -->
                        <div class="page-card mb-3" id="settings-appearance" style="display: none;">
                            <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Aparência</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Tema</label>
                                    <div class="d-flex gap-2">
                                        <div style="cursor: not-allowed; opacity: 0.6;">
                                            <input type="radio" name="theme" id="theme-light" value="light" disabled>
                                            <label for="theme-light" style="cursor: not-allowed;">
                                                <small>Claro</small>
                                            </label>
                                        </div>
                                        <div style="cursor: not-allowed; opacity: 0.6;">
                                            <input type="radio" name="theme" id="theme-dark" value="dark" disabled>
                                            <label for="theme-dark" style="cursor: not-allowed;">
                                                <small>Escuro</small>
                                            </label>
                                        </div>
                                        <div style="cursor: not-allowed; opacity: 0.6;">
                                            <input type="radio" name="theme" id="theme-auto" value="auto" disabled checked>
                                            <label for="theme-auto" style="cursor: not-allowed;">
                                                <small>Automático</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Compactação da barra lateral</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="compact-sidebar" disabled>
                                        <label class="form-check-label small" for="compact-sidebar">Usar barra lateral compacta por padrão</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notificações -->
                        <div class="page-card mb-3" id="settings-notifications" style="display: none;">
                            <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Preferências de Notificação</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notif-new-patients" disabled checked>
                                        <label class="form-check-label fw-bold small" for="notif-new-patients">Notificar sobre novos pacientes</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notif-new-doctors" disabled checked>
                                        <label class="form-check-label fw-bold small" for="notif-new-doctors">Notificar sobre novos médicos</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notif-bed-updates" disabled checked>
                                        <label class="form-check-label fw-bold small" for="notif-bed-updates">Notificar sobre mudanças de leitos</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notif-system-alerts" disabled checked>
                                        <label class="form-check-label fw-bold small" for="notif-system-alerts">Notificar sobre alertas do sistema</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Segurança -->
                        <div class="page-card mb-3" id="settings-security" style="display: none;">
                            <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Segurança</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Autenticação em duas etapas (2FA)</label>
                                    <p class="text-muted small mb-2">Adicione uma camada extra de segurança à sua conta.</p>
                                    <button type="button" class="btn btn-sm btn-outline-primary" disabled>
                                        <i class="bi bi-shield-check me-2"></i>Configurar 2FA
                                    </button>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Sessões ativas</label>
                                    <p class="text-muted small mb-0">Você tem 1 sessão ativa</p>
                                </div>
                            </div>
                        </div>

                        <!-- Privacidade -->
                        <div class="page-card mb-3" id="settings-privacy" style="display: none;">
                            <h6 class="fw-bold mb-4 text-uppercase text-muted" style="font-size: 0.8rem; letter-spacing: 0.08em;">Privacidade</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="show-online-status" disabled checked>
                                        <label class="form-check-label fw-bold small" for="show-online-status">Mostrar status online</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="show-activity" disabled checked>
                                        <label class="form-check-label fw-bold small" for="show-activity">Mostrar atividades recentes</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Dados pessoais</label>
                                    <p class="text-muted small mb-2">Controle como seus dados pessoais são utilizados.</p>
                                    <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                                        <i class="bi bi-download me-2"></i>Baixar meus dados
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php render_scripts(); ?>
