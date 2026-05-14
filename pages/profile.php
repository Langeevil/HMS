<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

$usuarioLogado = current_user();
$flash = get_flash('success');
$erro = null;
$passwordNotice = null;

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

    if ($formAction === 'password_update') {
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($newPassword === '' || $confirmPassword === '') {
            $erro = 'Informe e confirme a nova senha.';
        } elseif (strlen($newPassword) < 8) {
            $erro = 'A nova senha deve ter pelo menos 8 caracteres.';
        } elseif ($newPassword !== $confirmPassword) {
            $erro = 'A confirmação da senha não confere.';
        } else {
            $_SESSION['password_change_requested_at'] = date('c');
            $passwordNotice = 'A senha foi validada na interface, mas a API ainda precisa expor um endpoint de alteração de senha para persistir essa mudança.';
        }
    }
}

$nome = current_user_field('nome', current_user_name());
$username = current_user_field('username', current_user_field('login', '-'));
$email = current_user_field('email', '');
$telefone = current_user_field('telefone', '');
$cargo = current_user_field('cargo', 'Administrador');

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
                        <div class="profile-avatar" aria-hidden="true">
                            <i class="bi bi-person-fill"></i>
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
                                <dd><?= h($_SERVER['REMOTE_ADDR'] ?? 'desconhecido') ?></dd>
                            </div>
                        </dl>
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

                <section class="page-card mt-3" aria-labelledby="password-title">
                    <h2 class="h4 fw-bold mb-3" id="password-title">Alterar senha</h2>
                    <p class="text-muted">A validação é feita nesta tela. Para persistir a troca, conecte o formulário ao endpoint de senha quando ele existir na API Java.</p>
                    <form method="post" action="<?= h(url($basePath, 'pages/profile.php')) ?>">
                        <input type="hidden" name="form_action" value="password_update">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">Nova senha</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">Confirmar nova senha</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-key" aria-hidden="true"></i>Validar alteração</button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
<?php render_scripts(); ?>
