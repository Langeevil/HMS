<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$flash = get_flash('success');
$erro = null;
$settings = current_user_settings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = update_user_settings([
        'language' => (string) ($_POST['language'] ?? 'pt-BR'),
        'timezone' => (string) ($_POST['timezone'] ?? 'America/Sao_Paulo'),
        'system_notifications' => isset($_POST['system_notifications']),
        'usage_data' => isset($_POST['usage_data']),
        'theme' => in_array(($_POST['theme'] ?? 'auto'), ['auto', 'light', 'dark'], true) ? (string) $_POST['theme'] : 'auto',
        'compact_sidebar' => isset($_POST['compact_sidebar']),
        'notify_patients' => isset($_POST['notify_patients']),
        'notify_doctors' => isset($_POST['notify_doctors']),
        'notify_beds' => isset($_POST['notify_beds']),
        'notify_alerts' => isset($_POST['notify_alerts']),
        'show_online_status' => isset($_POST['show_online_status']),
        'show_recent_activity' => isset($_POST['show_recent_activity']),
    ]);

    set_flash('success', 'Configurações salvas com sucesso.');
    header('Location: ' . url($basePath, 'pages/settings.php'));
    exit;
}

function checked_setting(array $settings, string $key): string
{
    return !empty($settings[$key]) ? ' checked' : '';
}

function checked_value(array $settings, string $key, string $value): string
{
    return (string) ($settings[$key] ?? '') === $value ? ' checked' : '';
}

render_head('HMS - Configurações', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell">
        <div class="row g-0">
<?php render_admin_sidebar($basePath, ''); ?>
            <main class="col-lg-9 col-xl-10 content-area content-shell">
                <header class="top-header">
                    <div>
                        <h1 class="h2 fw-bold mb-1">Configurações</h1>
                        <p class="mb-0 text-muted">Ajuste tema, notificações, privacidade e preferências do painel.</p>
                    </div>
<?php render_user_menu(current_user_name(), url($basePath, 'pages/logout.php')); ?>
                </header>

<?php if ($flash): ?>
                <div class="alert alert-success" role="status"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
                <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
<?php endif; ?>

                <form method="post" action="<?= h(url($basePath, 'pages/settings.php')) ?>" data-settings-form>
                    <div class="settings-layout">
                        <nav class="page-card settings-nav" aria-label="Seções de configurações" role="tablist">
                            <button type="button" class="btn btn-sm btn-primary text-start active" data-settings-tab="settings-general" role="tab" aria-controls="settings-general" aria-selected="true">
                                <i class="bi bi-gear" aria-hidden="true"></i>Geral
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start" data-settings-tab="settings-appearance" role="tab" aria-controls="settings-appearance" aria-selected="false">
                                <i class="bi bi-palette" aria-hidden="true"></i>Aparência
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start" data-settings-tab="settings-notifications" role="tab" aria-controls="settings-notifications" aria-selected="false">
                                <i class="bi bi-bell" aria-hidden="true"></i>Notificações
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start" data-settings-tab="settings-security" role="tab" aria-controls="settings-security" aria-selected="false">
                                <i class="bi bi-shield-check" aria-hidden="true"></i>Segurança
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary text-start" data-settings-tab="settings-privacy" role="tab" aria-controls="settings-privacy" aria-selected="false">
                                <i class="bi bi-eye-slash" aria-hidden="true"></i>Privacidade
                            </button>
                        </nav>

                        <div class="settings-panels">
                            <section class="page-card settings-panel" id="settings-general" role="tabpanel" aria-labelledby="settings-general-title">
                                <h2 class="h4 fw-bold mb-3" id="settings-general-title">Configurações gerais</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="language">Idioma</label>
                                        <select class="form-select" name="language" id="language">
                                            <option value="pt-BR"<?= selected_attr($settings['language'], 'pt-BR') ?>>Português (Brasil)</option>
                                            <option value="pt-PT"<?= selected_attr($settings['language'], 'pt-PT') ?>>Português (Portugal)</option>
                                            <option value="en"<?= selected_attr($settings['language'], 'en') ?>>English</option>
                                            <option value="es"<?= selected_attr($settings['language'], 'es') ?>>Español</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="timezone">Fuso horário</label>
                                        <select class="form-select" name="timezone" id="timezone">
                                            <option value="America/Sao_Paulo"<?= selected_attr($settings['timezone'], 'America/Sao_Paulo') ?>>Horário de Brasília (UTC-3)</option>
                                            <option value="America/Manaus"<?= selected_attr($settings['timezone'], 'America/Manaus') ?>>Horário de Manaus (UTC-4)</option>
                                            <option value="America/Noronha"<?= selected_attr($settings['timezone'], 'America/Noronha') ?>>Horário de Fernando de Noronha (UTC-2)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="system_notifications" name="system_notifications"<?= checked_setting($settings, 'system_notifications') ?>>
                                            <label class="form-check-label" for="system_notifications">Ativar notificações do sistema</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="usage_data" name="usage_data"<?= checked_setting($settings, 'usage_data') ?>>
                                            <label class="form-check-label" for="usage_data">Compartilhar dados de uso com HMS</label>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="page-card settings-panel" id="settings-appearance" role="tabpanel" aria-labelledby="settings-appearance-title" hidden>
                                <h2 class="h4 fw-bold mb-3" id="settings-appearance-title">Aparência</h2>
                                <fieldset class="settings-fieldset">
                                    <legend class="form-label">Tema</legend>
                                    <div class="theme-options">
                                        <label class="theme-option">
                                            <input type="radio" name="theme" value="light"<?= checked_value($settings, 'theme', 'light') ?>>
                                            <span>Claro</span>
                                        </label>
                                        <label class="theme-option">
                                            <input type="radio" name="theme" value="dark"<?= checked_value($settings, 'theme', 'dark') ?>>
                                            <span>Escuro</span>
                                        </label>
                                        <label class="theme-option">
                                            <input type="radio" name="theme" value="auto"<?= checked_value($settings, 'theme', 'auto') ?>>
                                            <span>Automático</span>
                                        </label>
                                    </div>
                                </fieldset>
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" class="form-check-input" id="compact_sidebar" name="compact_sidebar"<?= checked_setting($settings, 'compact_sidebar') ?>>
                                    <label class="form-check-label" for="compact_sidebar">Usar barra lateral compacta por padrão</label>
                                </div>
                            </section>

                            <section class="page-card settings-panel" id="settings-notifications" role="tabpanel" aria-labelledby="settings-notifications-title" hidden>
                                <h2 class="h4 fw-bold mb-3" id="settings-notifications-title">Preferências de notificação</h2>
                                <div class="settings-check-list">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notify_patients" name="notify_patients"<?= checked_setting($settings, 'notify_patients') ?>>
                                        <label class="form-check-label" for="notify_patients">Notificar sobre novos pacientes</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notify_doctors" name="notify_doctors"<?= checked_setting($settings, 'notify_doctors') ?>>
                                        <label class="form-check-label" for="notify_doctors">Notificar sobre novos médicos</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notify_beds" name="notify_beds"<?= checked_setting($settings, 'notify_beds') ?>>
                                        <label class="form-check-label" for="notify_beds">Notificar sobre mudanças de leitos</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="notify_alerts" name="notify_alerts"<?= checked_setting($settings, 'notify_alerts') ?>>
                                        <label class="form-check-label" for="notify_alerts">Notificar sobre alertas do sistema</label>
                                    </div>
                                </div>
                            </section>

                            <section class="page-card settings-panel" id="settings-security" role="tabpanel" aria-labelledby="settings-security-title" hidden>
                                <h2 class="h4 fw-bold mb-3" id="settings-security-title">Segurança</h2>
                                <div class="settings-info-row">
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">Autenticação em duas etapas</h3>
                                        <p class="text-muted mb-0">Recurso previsto para quando a API expuser suporte a 2FA.</p>
                                    </div>
                                    <span class="badge bg-secondary">Indisponível</span>
                                </div>
                                <div class="settings-info-row">
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">Sessões ativas</h3>
                                        <p class="text-muted mb-0">Você tem 1 sessão ativa neste navegador.</p>
                                    </div>
                                    <a class="btn btn-sm btn-outline-danger" href="<?= h(url($basePath, 'pages/logout.php')) ?>">Encerrar sessão</a>
                                </div>
                            </section>

                            <section class="page-card settings-panel" id="settings-privacy" role="tabpanel" aria-labelledby="settings-privacy-title" hidden>
                                <h2 class="h4 fw-bold mb-3" id="settings-privacy-title">Privacidade</h2>
                                <div class="settings-check-list">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="show_online_status" name="show_online_status"<?= checked_setting($settings, 'show_online_status') ?>>
                                        <label class="form-check-label" for="show_online_status">Mostrar status online</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="show_recent_activity" name="show_recent_activity"<?= checked_setting($settings, 'show_recent_activity') ?>>
                                        <label class="form-check-label" for="show_recent_activity">Mostrar atividades recentes</label>
                                    </div>
                                </div>
                                <p class="text-muted mt-3 mb-0">Exportação de dados pessoais depende de endpoint específico da API.</p>
                            </section>

                            <div class="settings-actions">
                                <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle" aria-hidden="true"></i>Salvar configurações</button>
                                <a href="<?= h(url($basePath, 'pages/dashboard.php')) ?>" class="btn btn-secondary">Voltar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
    <script>
        (function () {
            const tabs = Array.from(document.querySelectorAll('[data-settings-tab]'));
            const panels = Array.from(document.querySelectorAll('.settings-panel'));
            const form = document.querySelector('[data-settings-form]');

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    const target = tab.dataset.settingsTab;
                    tabs.forEach(function (item) {
                        const active = item === tab;
                        item.classList.toggle('btn-primary', active);
                        item.classList.toggle('btn-outline-primary', !active);
                        item.classList.toggle('active', active);
                        item.setAttribute('aria-selected', active ? 'true' : 'false');
                    });
                    panels.forEach(function (panel) {
                        panel.hidden = panel.id !== target;
                    });
                });
            });

            if (form) {
                form.addEventListener('submit', function () {
                    const theme = form.querySelector('input[name="theme"]:checked')?.value || 'auto';
                    const compactSidebar = form.querySelector('#compact_sidebar')?.checked;
                    localStorage.setItem('hms-theme', theme);
                    localStorage.setItem('hms-sidebar-collapsed', compactSidebar ? '1' : '0');
                });
            }
        })();
    </script>
<?php render_scripts(); ?>
