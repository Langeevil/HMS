<?php
declare(strict_types=1);

require_once __DIR__ . '/../assets/config/api_comunication.php';

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
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

function render_head(string $title, string $basePath, bool $withIcons = false): void
{
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?></title>
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
</body>
</html>
<?php
}

function render_admin_sidebar(string $basePath, string $active): void
{
    $items = [
        'dashboard' => ['label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill', 'path' => 'pages/dashboard.php'],
        'medicos' => ['label' => 'Medicos', 'icon' => 'bi-person-badge', 'path' => 'pages/medicos/listar.php'],
        'pacientes' => ['label' => 'Pacientes', 'icon' => 'bi-people', 'path' => 'pages/pacientes/listar.php'],
        'leitos' => ['label' => 'Leitos', 'icon' => 'bi-hospital', 'path' => 'pages/leitos/listar.php'],
        'alas' => ['label' => 'Alas', 'icon' => 'bi-building', 'path' => 'pages/alas/listar.php'],
        'quartos' => ['label' => 'Quartos', 'icon' => 'bi-door-open', 'path' => 'pages/quartos/listar.php'],
        'especialidades' => ['label' => 'Especialidades', 'icon' => 'bi-clipboard2-pulse', 'path' => 'pages/especialidades/listar.php'],
    ];
    ?>
            <nav class="col-lg-3 col-xl-2 sidebar">
                <div class="sidebar-header">
                    <img src="<?= h(url($basePath, 'assets/images/logo.png')) ?>" alt="Logo HMS" class="brand-logo-image">
                    <div class="sidebar-brand-copy">
                        <span class="fw-bold fs-5">HMS Admin</span>
                        <small>Painel hospitalar</small>
                    </div>
                </div>
                <div class="sidebar-section">Operacao</div>
<?php foreach ($items as $key => $item): ?>
                <a href="<?= h(url($basePath, $item['path'])) ?>"<?= $key === $active ? ' class="active"' : '' ?>>
                    <i class="bi <?= h($item['icon']) ?>"></i>
                    <span><?= h($item['label']) ?></span>
                </a>
<?php endforeach; ?>
            </nav>
<?php
}

function render_user_menu(string $userName = 'Usuario', string $logoutHref = '#'): void
{
    ?>
                        <div class="dropdown">
                            <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-none d-md-block text-end">
                                    <div class="fw-bold small"><?= h($userName) ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;">Administrador</div>
                                </div>
                                <div class="user-icon">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i>Configuracoes</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?= h($logoutHref) ?>"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                            </ul>
                        </div>
<?php
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
