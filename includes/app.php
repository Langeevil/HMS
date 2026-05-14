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
        'pacientes' => ['label' => 'Pacientes', 'icon' => 'bi-people', 'path' => 'pages/pacientes/listar.php'],
        'medicos' => ['label' => 'Médicos', 'icon' => 'bi-person-badge', 'path' => 'pages/medicos/listar.php'],
        'consultas' => ['label' => 'Consultas', 'icon' => 'bi-calendar2-pulse', 'path' => 'pages/consultas/listar.php'],
        'receitas' => ['label' => 'Receitas', 'icon' => 'bi-prescription2', 'path' => 'pages/receitas/listar.php'],
        'medicamentos' => ['label' => 'Medicamentos', 'icon' => 'bi-capsule', 'path' => 'pages/medicamentos/listar.php'],
        'alas' => ['label' => 'Alas', 'icon' => 'bi-building', 'path' => 'pages/alas/listar.php'],
        'quartos' => ['label' => 'Quartos', 'icon' => 'bi-door-open', 'path' => 'pages/quartos/listar.php'],
        'leitos' => ['label' => 'Leitos', 'icon' => 'bi-hospital', 'path' => 'pages/leitos/listar.php'],
        'especialidades' => ['label' => 'Especialidades', 'icon' => 'bi-clipboard2-pulse', 'path' => 'pages/especialidades/listar.php'],
        'tipos-sanguineos' => ['label' => 'Tipos sanguíneos', 'icon' => 'bi-droplet-half', 'path' => 'pages/tipos-sanguineos/listar.php'],
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

function resource_config(string $resourceKey): array
{
    global $entityConfigs;

    return $entityConfigs[$resourceKey] ?? [];
}

function resource_rows(array $response): array
{
    $data = $response['data'] ?? [];

    if (isset($data['content']) && is_array($data['content'])) {
        return $data['content'];
    }

    if (isset($data['items']) && is_array($data['items'])) {
        return $data['items'];
    }

    if (isset($data['data']) && is_array($data['data'])) {
        return $data['data'];
    }

    if (is_array($data) && array_is_list($data)) {
        return $data;
    }

    return [];
}

function resource_field_fallbacks(string $fieldName): array
{
    return match ($fieldName) {
        'codtipodk' => ['tipoSanguineo.codtipo'],
        'codespecialidade' => ['especialidade.codespecialidade'],
        'codala' => ['ala.codala'],
        'codquarto' => ['quarto.codquarto'],
        'codpacientefk' => ['paciente.codpaciente'],
        'codmedicofk' => ['medico.codmedico'],
        'codconsultafk' => ['consulta.codconsulta'],
        'codreceitafk' => ['receita.codreceita'],
        'codmedicamentofk' => ['medicamento.codmedicamento'],
        'codexamefk' => ['exame.codexame'],
        default => [],
    };
}

function resource_value(array $record, string $path, mixed $default = ''): mixed
{
    $value = getNestedValue($record, $path, null);

    return $value === null || $value === '' ? $default : $value;
}

function resource_column_value(array $record, array $column): string
{
    $paths = array_merge([$column['path']], $column['fallback_paths'] ?? []);
    $values = [];

    foreach ($paths as $path) {
        $value = resource_value($record, (string) $path, '');
        if ($value !== '') {
            $values[] = $value;
        }
    }

    if ($values === []) {
        return '-';
    }

    return implode(' ', array_map(static fn (mixed $value): string => (string) $value, $values));
}

function resource_option_label(array $option, array $field): string
{
    if (!empty($field['option_label_fields']) && is_array($field['option_label_fields'])) {
        $parts = [];
        foreach ($field['option_label_fields'] as $path) {
            $value = resource_value($option, (string) $path, '');
            if ($value !== '') {
                $parts[] = (string) $value;
            }
        }

        return $parts !== [] ? implode(' - ', $parts) : '-';
    }

    if (!empty($field['option_label'])) {
        return (string) resource_value($option, (string) $field['option_label'], '-');
    }

    return (string) ($option['label'] ?? $option['value'] ?? '-');
}

function resource_field_options(array $field): array
{
    if (!empty($field['options']) && is_array($field['options'])) {
        return $field['options'];
    }

    if (empty($field['options_resource'])) {
        return [];
    }

    $response = fetchResourceList((string) $field['options_resource']);

    return resource_rows($response);
}

function resource_delete_query(string $resourceKey, array $record): string
{
    $config = resource_config($resourceKey);
    $query = ['delete' => '1'];

    foreach ($config['primary_keys'] ?? [] as $key) {
        $value = resource_value($record, (string) $key, '');
        if ($value === '') {
            foreach (resource_field_fallbacks((string) $key) as $fallback) {
                $value = resource_value($record, $fallback, '');
                if ($value !== '') {
                    break;
                }
            }
        }
        $query[$key] = $value;
    }

    return http_build_query($query);
}

function resource_id_from_request(string $resourceKey, array $data): string|array|null
{
    $config = resource_config($resourceKey);
    $primaryData = [];

    foreach ($config['primary_keys'] ?? [] as $key) {
        $source = '__pk_' . $key;
        if (isset($data[$source]) && $data[$source] !== '') {
            $primaryData[$key] = $data[$source];
        }
    }

    if ($primaryData !== []) {
        return resourceIdFromData($resourceKey, $primaryData);
    }

    return resourceIdFromData($resourceKey, $data);
}

function render_resource_field(array $field, array $record = [], string $idPrefix = ''): void
{
    $name = (string) $field['name'];
    $type = (string) ($field['type'] ?? 'text');
    $id = $idPrefix . $name;
    $value = resource_value($record, $name, '');
    $required = !empty($field['required']) ? ' required' : '';
    $fallbacks = resource_field_fallbacks($name);
    $dataSource = h($name);
    $dataFallbacks = h(json_encode($fallbacks, JSON_UNESCAPED_UNICODE) ?: '[]');
    ?>
                        <div class="mb-3">
                            <label for="<?= h($id) ?>" class="form-label"><?= h($field['label'] ?? $name) ?></label>
<?php if ($type === 'textarea'): ?>
                            <textarea class="form-control js-resource-field" name="<?= h($name) ?>" id="<?= h($id) ?>" rows="3" data-source="<?= $dataSource ?>" data-fallbacks="<?= $dataFallbacks ?>"<?= $required ?>><?= h($value) ?></textarea>
<?php elseif ($type === 'select'): ?>
                            <select class="form-select js-resource-field" name="<?= h($name) ?>" id="<?= h($id) ?>" data-source="<?= $dataSource ?>" data-fallbacks="<?= $dataFallbacks ?>"<?= $required ?>>
                                <option value="">Selecione...</option>
<?php foreach (resource_field_options($field) as $option): ?>
<?php
    $optionValuePath = $field['option_value'] ?? null;
    $optionValue = $optionValuePath !== null ? resource_value($option, (string) $optionValuePath, '') : ($option['value'] ?? '');
?>
                                <option value="<?= h($optionValue) ?>"<?= selected_attr($value, $optionValue) ?>><?= h(resource_option_label($option, $field)) ?></option>
<?php endforeach; ?>
                            </select>
<?php else: ?>
                            <input type="<?= h($type) ?>" class="form-control js-resource-field" name="<?= h($name) ?>" id="<?= h($id) ?>" value="<?= h($value) ?>" data-source="<?= $dataSource ?>" data-fallbacks="<?= $dataFallbacks ?>"<?= $required ?>>
<?php endif; ?>
                        </div>
<?php
}

function render_resource_form_page(string $resourceKey, string $basePath, string $activeKey, string $title, string $description): void
{
    $config = resource_config($resourceKey);
    $usuarioLogado = current_user();
    $record = [];
    $erro = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $record = $_POST;
        $response = saveResource($resourceKey, $_POST);

        if ($response['success']) {
            set_flash('success', $config['singular'] . ' salvo com sucesso.');
            header('Location: ' . url($basePath, 'pages/' . $resourceKey . '/listar.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Não foi possível salvar o registro.';
    }

    render_head('HMS - ' . $title, $basePath, true);
    ?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, $activeKey); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1"><?= h($title) ?></h1><p class="text-muted mb-0"><?= h($description) ?></p></div><div class="toolbar-actions"><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuário', url($basePath, 'pages/logout.php')); ?></div></div>
            <div class="page-card"><form action="<?= h(url($basePath, 'pages/' . $resourceKey . '/form.php')) ?>" method="post">
<?php if ($erro): ?>
                <div class="alert alert-danger mb-3" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
                <div class="row g-3">
<?php foreach ($config['fields'] ?? [] as $field): ?>
                    <div class="col-md-6"><?php render_resource_field($field, $record); ?></div>
<?php endforeach; ?>
                </div>
                <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-success">Salvar</button><a href="<?= h(url($basePath, 'pages/' . $resourceKey . '/listar.php')) ?>" class="btn btn-secondary">Cancelar</a></div>
            </form></div>
        </main>
    </div></div>
<?php render_scripts(); ?>
<?php
}

function render_resource_list_page(string $resourceKey, string $basePath, string $activeKey, string $title, string $description): void
{
    $config = resource_config($resourceKey);
    $usuarioLogado = current_user();
    $flash = get_flash('success');
    $erro = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'edit_resource') {
        $id = resource_id_from_request($resourceKey, $_POST);
        $response = $id !== null
            ? updateResource($resourceKey, $id, $_POST)
            : ['success' => false, 'error' => 'Identificador do registro não informado.'];

        if ($response['success']) {
            set_flash('success', $config['singular'] . ' atualizado com sucesso.');
            header('Location: ' . url($basePath, 'pages/' . $resourceKey . '/listar.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Não foi possível atualizar o registro.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['delete'] ?? '') === '1') {
        $id = resource_id_from_request($resourceKey, $_GET);
        $response = $id !== null
            ? deleteResource($resourceKey, $id)
            : ['success' => false, 'error' => 'Identificador do registro não informado.'];

        if ($response['success']) {
            set_flash('success', $config['singular'] . ' excluído com sucesso.');
            header('Location: ' . url($basePath, 'pages/' . $resourceKey . '/listar.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Não foi possível excluir o registro.';
    }

    $response = fetchResourceList($resourceKey);
    $rows = resource_rows($response);
    $columns = $config['columns'] ?? [];

    render_head('HMS - ' . $title, $basePath, true);
    ?>
<body class="admin-page">
    <div class="container-fluid admin-shell"><div class="row g-0">
<?php render_admin_sidebar($basePath, $activeKey); ?>
        <main class="col-lg-9 col-xl-10 content">
            <div class="page-toolbar"><div><h1 class="h2 fw-bold mb-1"><?= h($title) ?></h1><p class="text-muted mb-0"><?= h($description) ?></p></div><div class="toolbar-actions"><a href="<?= h(url($basePath, 'pages/' . $resourceKey . '/form.php')) ?>" class="btn btn-primary">Novo <?= h((string) $config['singular']) ?></a><?php render_user_menu($usuarioLogado['nome'] ?? 'Usuário', url($basePath, 'pages/logout.php')); ?></div></div>
<?php if ($flash): ?>
            <div class="alert alert-success" role="status"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
            <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
            <div class="page-card table-shell"><table class="table table-hover align-middle"><thead><tr>
<?php foreach ($columns as $column): ?>
                <th scope="col"><?= h($column['label']) ?></th>
<?php endforeach; ?>
                <th scope="col" class="text-end">Ações</th>
            </tr></thead><tbody>
<?php if ($rows === []): ?><?php empty_table_row(count($columns) + 1, 'Nenhum registro carregado.'); ?><?php else: foreach ($rows as $row): ?>
                <tr>
<?php foreach ($columns as $column): ?>
                    <td><?= h(resource_column_value($row, $column)) ?></td>
<?php endforeach; ?>
                    <td class="text-end"><button type="button" class="btn btn-sm btn-warning js-edit-resource" data-record="<?= json_attr($row) ?>" data-bs-toggle="modal" data-bs-target="#editResourceModal">Editar</button> <a href="<?= h(url($basePath, 'pages/' . $resourceKey . '/listar.php?' . resource_delete_query($resourceKey, $row))) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a></td>
                </tr>
<?php endforeach; endif; ?>
            </tbody></table></div>
        </main>
    </div></div>

    <div class="modal fade" id="editResourceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/' . $resourceKey . '/listar.php')) ?>">
                    <div class="modal-header">
                        <h2 class="h5 modal-title">Editar <?= h((string) $config['singular']) ?></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="edit_resource">
<?php foreach ($config['primary_keys'] ?? [] as $primaryKey): ?>
                        <input type="hidden" name="__pk_<?= h($primaryKey) ?>" id="edit-pk-<?= h($primaryKey) ?>" class="js-resource-primary" data-source="<?= h($primaryKey) ?>" data-fallbacks="<?= h(json_encode(resource_field_fallbacks((string) $primaryKey), JSON_UNESCAPED_UNICODE) ?: '[]') ?>">
<?php endforeach; ?>
<?php foreach ($config['fields'] ?? [] as $field): ?>
<?php render_resource_field($field, [], 'edit-'); ?>
<?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function () {
            const readPath = function (record, path) {
                return String(path || '').split('.').reduce(function (current, segment) {
                    return current && Object.prototype.hasOwnProperty.call(current, segment) ? current[segment] : undefined;
                }, record);
            };

            const resolveValue = function (record, source, fallbacks) {
                const paths = [source].concat(fallbacks || []);
                for (const path of paths) {
                    const value = readPath(record, path);
                    if (value !== undefined && value !== null && value !== '') {
                        return value;
                    }
                }
                return '';
            };

            document.querySelectorAll('.js-edit-resource').forEach(function (button) {
                button.addEventListener('click', function () {
                    const record = JSON.parse(button.dataset.record || '{}');
                    document.querySelectorAll('#editResourceModal [data-source]').forEach(function (field) {
                        const fallbacks = JSON.parse(field.dataset.fallbacks || '[]');
                        field.value = resolveValue(record, field.dataset.source, fallbacks);
                    });
                });
            });
        })();
    </script>
<?php render_scripts(); ?>
<?php
}
