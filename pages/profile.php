<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

function profile_password_errors(string $password): array
{
    $rules = [
        [strlen($password) >= 8, 'A senha deve ter no mínimo 8 caracteres.'],
        [preg_match('/[A-Z]/', $password) === 1, 'Inclua pelo menos uma letra maiúscula.'],
        [preg_match('/[a-z]/', $password) === 1, 'Inclua pelo menos uma letra minúscula.'],
        [preg_match('/\d/', $password) === 1, 'Inclua pelo menos um número.'],
        [preg_match('/[^A-Za-z0-9\s]/', $password) === 1, 'Inclua pelo menos um caractere especial.'],
        [preg_match('/\s/', $password) !== 1, 'A senha não pode conter espaços.'],
    ];

    return array_values(array_map(
        static fn (array $rule): string => $rule[1],
        array_filter($rules, static fn (array $rule): bool => $rule[0] === false)
    ));
}

function profile_client_ip(): string
{
    $candidates = [
        $_SERVER['HTTP_CLIENT_IP'] ?? '',
        $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
        $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    foreach ($candidates as $candidate) {
        $ip = trim(explode(',', (string) $candidate)[0]);

        if ($ip === '') {
            continue;
        }

        if ($ip === '::1' || $ip === '0:0:0:0:0:0:0:1') {
            return '127.0.0.1 (localhost)';
        }

        return $ip;
    }

    return 'desconhecido';
}

function profile_uploaded_photo_base64(array $file): array
{
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'error' => $error === UPLOAD_ERR_NO_FILE ? 'Selecione uma foto para enviar.' : 'Não foi possível receber a foto enviada.',
        ];
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        return ['success' => false, 'error' => 'Arquivo de foto inválido.'];
    }

    $maxBytes = 1024 * 1024;
    if ((int) ($file['size'] ?? 0) > $maxBytes) {
        return ['success' => false, 'error' => 'A foto deve ter no máximo 1 MB.'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo !== false ? finfo_file($finfo, $tmpName) : false;
    if ($finfo !== false) {
        finfo_close($finfo);
    }

    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!is_string($mime) || !in_array($mime, $allowedMimes, true)) {
        return ['success' => false, 'error' => 'Use uma imagem PNG, JPG ou WEBP.'];
    }

    $content = file_get_contents($tmpName);
    if ($content === false) {
        return ['success' => false, 'error' => 'Não foi possível ler a foto enviada.'];
    }

    return [
        'success' => true,
        'data' => 'data:' . $mime . ';base64,' . base64_encode($content),
    ];
}

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;
$passwordNotice = null;
$usernameForAuth = current_user_field('username', current_user_field('login', ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';

    if ($formAction === 'profile_update') {
        $nome = trim((string) ($_POST['nome'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $telefone = trim((string) ($_POST['telefone'] ?? ''));
        $cargo = trim((string) ($_POST['cargo'] ?? 'Administrador'));

        if ($nome === '') {
            $erro = 'Informe o nome completo.';
        } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'Informe um e-mail válido.';
        } else {
            $usuarioLogado = update_current_user([
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'cargo' => $cargo !== '' ? $cargo : 'Administrador',
            ]);

            set_flash('success', 'Perfil atualizado com sucesso.');
            header('Location: ' . url($basePath, 'pages/profile.php'));
            exit;
        }
    }

    if ($formAction === 'profile_photo_update') {
        if ($usernameForAuth === '') {
            $erro = 'Usuário não encontrado na sessão.';
        } elseif (($_POST['remove_photo'] ?? '') === '1') {
            $response = removeUserPhoto($usernameForAuth);

            if ($response['success']) {
                update_current_user(['fotoBase64' => '']);
                set_flash('success', 'Foto de perfil removida com sucesso.');
                header('Location: ' . url($basePath, 'pages/profile.php'));
                exit;
            }

            $erro = $response['error'] ?: 'Não foi possível remover a foto.';
        } else {
            $photo = profile_uploaded_photo_base64($_FILES['profile_photo'] ?? []);

            if (!$photo['success']) {
                $erro = $photo['error'];
            } else {
                $response = updateUserPhoto($usernameForAuth, (string) $photo['data']);

                if ($response['success']) {
                    $fotoBase64 = is_array($response['data'] ?? null)
                        ? (string) ($response['data']['fotoBase64'] ?? $photo['data'])
                        : (string) $photo['data'];

                    update_current_user(['fotoBase64' => $fotoBase64]);
                    set_flash('success', 'Foto de perfil atualizada com sucesso.');
                    header('Location: ' . url($basePath, 'pages/profile.php'));
                    exit;
                }

                $erro = $response['error'] ?: 'Não foi possível atualizar a foto.';
            }
        }
    }

    if ($formAction === 'password_update') {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $passwordErrors = profile_password_errors($newPassword);

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $erro = 'Informe a senha atual, a nova senha e a confirmação.';
        } elseif ($usernameForAuth === '' || !authenticateUser($usernameForAuth, $currentPassword)['success']) {
            $erro = 'A senha atual não confere.';
        } elseif ($passwordErrors !== []) {
            $erro = 'A nova senha ainda não atende aos requisitos: ' . implode(' ', $passwordErrors);
        } elseif ($newPassword !== $confirmPassword) {
            $erro = 'A confirmação da senha não confere.';
        } else {
            $_SESSION['password_change_requested_at'] = date('c');
            $passwordNotice = 'Senha atual confirmada e nova senha validada. A API Java ainda precisa expor um endpoint de alteração de senha para persistir essa mudança.';
        }
    }
}

$nome = current_user_field('nome', current_user_name());
$username = current_user_field('username', current_user_field('login', '-'));
$email = current_user_field('email', '');
$telefone = current_user_field('telefone', '');
$cargo = current_user_field('cargo', 'Administrador');
$fotoBase64 = current_user_field('fotoBase64', '');

render_head('HMS - Perfil do Usuário', $basePath, true);
?>
<body class="admin-page">
    <div class="container-fluid admin-shell">
        <div class="row g-0">
<?php render_admin_sidebar($basePath, ''); ?>
            <main class="col-lg-9 col-xl-10 content-area content-shell">
                <header class="top-header">
                    <div>
                        <h1 class="h2 fw-bold mb-1">Meu perfil</h1>
                        <p class="mb-0 text-muted">Atualize seus dados de usuário e revise informações da conta.</p>
                    </div>
<?php render_user_menu(current_user_name(), url($basePath, 'pages/logout.php')); ?>
                </header>

<?php if ($flash): ?>
                <div class="alert alert-success" role="status"><?= h($flash) ?></div>
<?php endif; ?>
<?php if ($erro): ?>
                <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
<?php if ($passwordNotice): ?>
                <div class="alert alert-warning" role="status"><?= h($passwordNotice) ?></div>
<?php endif; ?>

                <div class="profile-grid">
                    <section class="page-card profile-summary" aria-labelledby="profile-summary-title">
                        <div class="profile-photo-editor" data-profile-photo-editor>
                            <div class="profile-avatar<?= $fotoBase64 !== '' ? ' has-photo' : '' ?>">
<?php if ($fotoBase64 !== ''): ?>
                                <img src="<?= h($fotoBase64) ?>" alt="Foto de perfil de <?= h($nome) ?>" data-profile-photo-preview>
<?php else: ?>
                                <i class="bi bi-person-fill"></i>
<?php endif; ?>
                            </div>
                            <form method="post" action="<?= h(url($basePath, 'pages/profile.php')) ?>" enctype="multipart/form-data" class="profile-photo-form" data-profile-photo-form>
                                <input type="hidden" name="form_action" value="profile_photo_update">
                                <button type="button" class="profile-photo-edit" data-profile-photo-toggle aria-expanded="false" aria-controls="profilePhotoBubble" aria-label="Editar foto de perfil">
                                    <i class="bi bi-pencil-fill" aria-hidden="true"></i>
                                </button>
                                <div class="profile-photo-bubble" id="profilePhotoBubble" data-profile-photo-bubble hidden>
                                    <div class="profile-photo-actions">
                                        <button type="submit" class="btn btn-sm btn-primary" data-profile-photo-submit disabled>Salvar foto</button>
                                        <label for="profile_photo" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-camera" aria-hidden="true"></i><?= $fotoBase64 !== '' ? 'Alterar foto' : 'Adicionar foto' ?>
                                        </label>
<?php if ($fotoBase64 !== ''): ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" name="remove_photo" value="1">Remover</button>
<?php endif; ?>
                                    </div>
                                    <input type="file" class="visually-hidden" id="profile_photo" name="profile_photo" accept="image/png,image/jpeg,image/webp" data-profile-photo-input>
                                    <p class="profile-photo-hint" data-profile-photo-name>PNG, JPG ou WEBP até 1 MB.</p>
                                </div>
                            </form>
                        </div>
                        <h2 class="h4 fw-bold mt-3 mb-1" id="profile-summary-title"><?= h($nome) ?></h2>
                        <p class="text-muted mb-3"><?= h($cargo) ?></p>
                        <dl class="profile-meta">
                            <div>
                                <dt>Usuário</dt>
                                <dd><?= h($username) ?></dd>
                            </div>
                            <div>
                                <dt>Status</dt>
                                <dd><span class="badge bg-success">Ativo</span></dd>
                            </div>
                            <div>
                                <dt>IP atual</dt>
                                <dd><?= h(profile_client_ip()) ?></dd>
                            </div>
                        </dl>
                        <button type="button" class="btn btn-outline-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#passwordModal">
                            <i class="bi bi-key" aria-hidden="true"></i>Alterar senha
                        </button>
                    </section>

                    <section class="page-card" aria-labelledby="profile-edit-title">
                        <h2 class="h4 fw-bold mb-3" id="profile-edit-title">Dados do perfil</h2>
                        <form method="post" action="<?= h(url($basePath, 'pages/profile.php')) ?>">
                            <input type="hidden" name="form_action" value="profile_update">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nome" class="form-label">Nome completo</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?= h($nome) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Usuário</label>
                                    <input type="text" class="form-control" id="username" value="<?= h($username) ?>" readonly aria-describedby="username-help">
                                    <div class="form-text" id="username-help">O usuário de acesso vem da autenticação da API.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= h($email) ?>" placeholder="nome@hospital.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone" name="telefone" value="<?= h($telefone) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="cargo" class="form-label">Função</label>
                                    <input type="text" class="form-control" id="cargo" name="cargo" value="<?= h($cargo) ?>">
                                </div>
                            </div>
                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle" aria-hidden="true"></i>Salvar perfil</button>
                                <a href="<?= h(url($basePath, 'pages/dashboard.php')) ?>" class="btn btn-secondary">Voltar</a>
                            </div>
                        </form>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade password-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="<?= h(url($basePath, 'pages/profile.php')) ?>" data-profile-password-form novalidate>
                    <div class="modal-header">
                        <div>
                            <h2 class="h5 modal-title" id="passwordModalTitle">Alterar senha</h2>
                            <p class="text-muted mb-0 small">Confirme sua senha atual e escolha uma nova senha forte.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_action" value="password_update">
                        <div class="profile-password-alert" data-profile-password-message role="alert" aria-live="polite"></div>

                        <div class="profile-password-field">
                            <label for="currentPassword" class="form-label">Senha atual</label>
                            <div class="profile-password-input-wrap">
                                <input type="password" class="profile-password-input" id="currentPassword" name="current_password" autocomplete="current-password" required>
                                <button type="button" class="profile-password-toggle" data-target="currentPassword">Mostrar</button>
                            </div>
                        </div>

                        <div class="profile-password-field">
                            <label for="profileNewPassword" class="form-label">Nova senha</label>
                            <div class="profile-password-input-wrap" id="profilePasswordWrap">
                                <input type="password" class="profile-password-input" id="profileNewPassword" name="new_password" autocomplete="new-password" required>
                                <button type="button" class="profile-password-toggle" data-target="profileNewPassword">Mostrar</button>
                            </div>

                            <div class="profile-strength-row">
                                <span id="profileStrengthText" class="profile-strength-text weak">Digite uma senha segura</span>
                                <span id="profileStrengthCount">0/6</span>
                            </div>

                            <div class="profile-strength-bar" aria-hidden="true">
                                <div class="profile-bar-fill" id="profileBarFill"></div>
                            </div>

                            <div class="profile-password-bubble" id="profilePasswordBubble">
                                <div class="profile-bubble-head">
                                    <div>
                                        <div class="profile-bubble-title" id="profileBubbleTitle">Força da senha</div>
                                        <div class="profile-bubble-sub" id="profileBubbleSub">0 de 6 requisitos</div>
                                    </div>
                                    <i class="bi bi-shield-lock" aria-hidden="true"></i>
                                </div>
                                <div class="profile-strength-bar profile-bubble-bar" aria-hidden="true">
                                    <div class="profile-bar-fill" id="profileBubbleBarFill"></div>
                                </div>
                                <div class="profile-rule" id="profileRuleLength"><span class="profile-rule-icon">x</span>Mínimo de 8 caracteres</div>
                                <div class="profile-rule" id="profileRuleUpper"><span class="profile-rule-icon">x</span>Uma letra maiúscula</div>
                                <div class="profile-rule" id="profileRuleLower"><span class="profile-rule-icon">x</span>Uma letra minúscula</div>
                                <div class="profile-rule" id="profileRuleNumber"><span class="profile-rule-icon">x</span>Um número</div>
                                <div class="profile-rule" id="profileRuleSpecial"><span class="profile-rule-icon">x</span>Um caractere especial</div>
                                <div class="profile-rule" id="profileRuleNoSpaces"><span class="profile-rule-icon">x</span>Sem espaços</div>
                            </div>
                        </div>

                        <div class="profile-password-field">
                            <label for="profileConfirmPassword" class="form-label">Confirmar nova senha</label>
                            <div class="profile-password-input-wrap" id="profileConfirmWrap">
                                <input type="password" class="profile-password-input" id="profileConfirmPassword" name="confirm_password" autocomplete="new-password" required>
                                <button type="button" class="profile-password-toggle" data-target="profileConfirmPassword">Mostrar</button>
                            </div>
                            <div class="profile-confirm-msg" id="profileConfirmMsg" aria-live="polite"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="profilePasswordSubmit" disabled>Confirmar alteração</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= h(url($basePath, 'assets/js/profile-photo.js')) ?>"></script>
    <script src="<?= h(url($basePath, 'assets/js/profile-password.js')) ?>"></script>
<?php render_scripts(); ?>
