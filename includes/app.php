<?php
declare(strict_types=1);

require_once __DIR__ . '/../assets/config/api_comunication.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function json_attr(mixed $value): string
{
    $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return h($json === false ? '{}' : $json);
}

function url(string $basePath, string $path = ''): string
{
    $base = rtrim($basePath, '/');
    $target = ltrim($path, '/');

    if ($base === '' || $base === '.') {
        return $target === '' ? '.' : './' . $target;
    }

    return $target === '' ? $base : $base . '/' . $target;
}

function url_from_page(string $page): string
{
    return './' . $page;
}

function render_head(string $title, string $basePath, bool $withIcons = false): void
{
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?></title>
    <script>
        (function () {
            const theme = localStorage.getItem('hms-theme');
            if (theme && theme !== 'auto') {
                document.documentElement.dataset.theme = theme;
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php if ($withIcons): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php endif; ?>
    <link rel="stylesheet" href="<?= h(url($basePath, 'assets/css/hms-theme.css')) ?>">
</head>
<?php
}

function render_scripts(): void
{
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const toggle = document.querySelector('[data-sidebar-toggle]');
            if (!toggle) {
                return;
            }

            const storageKey = 'hms-sidebar-collapsed';
            const applyState = function (collapsed) {
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                toggle.setAttribute('aria-label', collapsed ? 'Expandir menu lateral' : 'Recolher menu lateral');
            };

            applyState(localStorage.getItem(storageKey) === '1');

            toggle.addEventListener('click', function () {
                const collapsed = !document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem(storageKey, collapsed ? '1' : '0');
                applyState(collapsed);
            });
        })();
    </script>
</body>
</html>
<?php
}

function render_admin_sidebar(string $basePath, string $active): void
{
    $items = [
        'dashboard' => ['label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill', 'path' => 'pages/dashboard.php'],
        'medicos' => ['label' => 'Médicos', 'icon' => 'bi-person-badge', 'path' => 'pages/medicos/listar.php'],
        'pacientes' => ['label' => 'Pacientes', 'icon' => 'bi-people', 'path' => 'pages/pacientes/listar.php'],
        'leitos' => ['label' => 'Leitos', 'icon' => 'bi-hospital', 'path' => 'pages/leitos/listar.php'],
        'alas' => ['label' => 'Alas', 'icon' => 'bi-building', 'path' => 'pages/alas/listar.php'],
        'quartos' => ['label' => 'Quartos', 'icon' => 'bi-door-open', 'path' => 'pages/quartos/listar.php'],
        'especialidades' => ['label' => 'Especialidades', 'icon' => 'bi-clipboard2-pulse', 'path' => 'pages/especialidades/listar.php'],
    ];
    ?>
            <nav class="col-lg-3 col-xl-2 sidebar" aria-label="Navegação administrativa">
                <div class="sidebar-header">
                    <img src="<?= h(url($basePath, 'assets/images/logo.png')) ?>" alt="Logo HMS" class="brand-logo-image">
                    <div class="sidebar-brand-copy">
                        <span class="fw-bold fs-5">HMS Admin</span>
                        <small>Painel hospitalar</small>
                    </div>
                    <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-expanded="true" aria-label="Recolher menu lateral">
                        <i class="bi bi-layout-sidebar-inset" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="sidebar-section">Operação</div>
<?php foreach ($items as $key => $item): ?>
                <a href="<?= h(url($basePath, $item['path'])) ?>"<?= $key === $active ? ' class="active" aria-current="page"' : '' ?>>
                    <i class="bi <?= h($item['icon']) ?>" aria-hidden="true"></i>
                    <span><?= h($item['label']) ?></span>
                </a>
<?php endforeach; ?>
            </nav>
<?php
}

function render_user_menu(string $userName = 'Usuário', string $logoutHref = '#'): void
{
    $profileHref = $logoutHref !== '#'
        ? preg_replace('/logout\.php(?:\?.*)?$/', 'profile.php', $logoutHref)
        : url_from_page('profile.php');
    $settingsHref = $logoutHref !== '#'
        ? preg_replace('/logout\.php(?:\?.*)?$/', 'settings.php', $logoutHref)
        : url_from_page('settings.php');
    ?>
                        <div class="dropdown">
                            <button type="button" class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Abrir menu do usuário">
                                <div class="d-none d-md-block text-end">
                                    <div class="fw-bold small"><?= h($userName) ?></div>
                                    <div class="text-muted user-role">Administrador</div>
                                </div>
                                <div class="user-icon">
                                    <i class="bi bi-person-fill" aria-hidden="true"></i>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item py-2" href="<?= h($profileHref ?? url_from_page('profile.php')) ?>"><i class="bi bi-person me-2" aria-hidden="true"></i>Perfil</a></li>
                                <li><a class="dropdown-item py-2" href="<?= h($settingsHref ?? url_from_page('settings.php')) ?>"><i class="bi bi-gear me-2" aria-hidden="true"></i>Configurações</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?= h($logoutHref) ?>"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Sair</a></li>
                            </ul>
                        </div>
<?php
}

function current_user(): array
{
    $user = $_SESSION['auth_user'] ?? null;

    return is_array($user) ? $user : [];
}

function current_user_name(): string
{
    $user = current_user();

    foreach (['nome', 'name', 'username', 'login'] as $field) {
        if (!empty($user[$field]) && is_scalar($user[$field])) {
            return (string) $user[$field];
        }
    }

    return 'Usuário';
}

function current_user_field(string $field, string $default = ''): string
{
    $user = current_user();
    $value = $user[$field] ?? null;

    return is_scalar($value) && $value !== '' ? (string) $value : $default;
}

function update_current_user(array $data): array
{
    $user = current_user();

    foreach ($data as $key => $value) {
        if (is_scalar($value)) {
            $user[$key] = trim((string) $value);
        }
    }

    $_SESSION['auth_user'] = $user;

    return $user;
}

function default_user_settings(): array
{
    return [
        'language' => 'pt-BR',
        'timezone' => 'America/Sao_Paulo',
        'system_notifications' => true,
        'usage_data' => false,
        'theme' => 'auto',
        'compact_sidebar' => false,
        'notify_patients' => true,
        'notify_doctors' => true,
        'notify_beds' => true,
        'notify_alerts' => true,
        'show_online_status' => true,
        'show_recent_activity' => true,
    ];
}

function current_user_settings(): array
{
    $settings = $_SESSION['user_settings'] ?? [];

    return array_merge(default_user_settings(), is_array($settings) ? $settings : []);
}

function update_user_settings(array $settings): array
{
    $_SESSION['user_settings'] = array_merge(default_user_settings(), $settings);

    return $_SESSION['user_settings'];
}

function is_authenticated(): bool
{
    return current_user() !== [];
}

function require_authentication(string $redirectTo = '../login.php'): void
{
    if (is_authenticated()) {
        return;
    }

    header('Location: ' . $redirectTo);
    exit;
}

function set_flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);

    return is_string($message) ? $message : null;
}

function selected_attr(mixed $current, mixed $expected): string
{
    return (string) $current === (string) $expected ? ' selected' : '';
}

function empty_table_row(int $columns, string $message): void
{
    ?>
                            <tr>
                                <td colspan="<?= h($columns) ?>" class="text-center text-muted py-4"><?= h($message) ?></td>
                            </tr>
<?php
}
