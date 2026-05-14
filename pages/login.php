<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

if (is_authenticated()) {
    header('Location: ' . url($basePath, 'pages/dashboard.php'));
    exit;
}

$erro = null;
$formAction = $formAction ?? url($basePath, 'pages/login.php');
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $erro = 'Informe usuário e senha.';
    } else {
        $response = authenticateUser($username, $password);

        if ($response['success']) {
            $apiUser = [];
            $responseData = $response['data'];
            $apiSessionCookie = $response['headers']['set-cookie'] ?? null;

            if (is_array($responseData)) {
                $apiUser = $responseData['user'] ?? $responseData['usuario'] ?? $responseData;
            }

            if (!is_array($apiUser)) {
                $apiUser = [];
            }

            if ($apiUser === []) {
                $apiUser = ['username' => $username];
            }

            $_SESSION['auth_user'] = $apiUser;
            $_SESSION['api_session_cookie'] = $apiSessionCookie;

            header('Location: ' . url($basePath, 'pages/dashboard.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Falha ao autenticar na API.';
    }
}

render_head('HMS - Login', $basePath);
?>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-highlight d-flex flex-column justify-content-between">
            <div>
                <img src="<?= h(url($basePath, 'assets/images/logo.png')) ?>" alt="Logo HMS" class="brand-logo-image">
                <h1 class="display-5 fw-bold mt-4 mb-3">Acesso ao centro de gestão hospitalar.</h1>
                <p class="mb-0 text-white-50 fs-5">Acesse pacientes, médicos, quartos, leitos e demais cadastros do hospital.</p>
            </div>
            <div class="pt-4">
                <p class="mb-1 fw-semibold">HMS Admin</p>
                <small class="text-white-50">Controle administrativo para a rotina operacional da unidade.</small>
            </div>
        </section>
        <section class="auth-panel">
            <a href="<?= h(url($basePath, 'index.php')) ?>" class="auth-back d-inline-block mb-4">&larr; Voltar</a>
            <div class="auth-card card">
                <div class="card-body p-0">
                    <h2 class="h3 fw-bold mb-2">Entrar no HMS</h2>
                    <p class="text-muted mb-4">Informe usuário e senha para continuar.</p>
                    <form action="<?= h($formAction) ?>" method="post">
<?php if ($erro): ?>
                        <div class="alert alert-danger" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
                        <div class="mb-3"><label for="username" class="form-label">Usuário</label><input type="text" name="username" value="<?= h($username) ?>" class="form-control" id="username" required></div>
                        <div class="mb-4"><label for="password" class="form-label">Senha</label><input type="password" name="password" class="form-control" id="password" required></div>
                        <button class="w-100 btn btn-primary btn-lg" type="submit">Entrar</button>
                        <div class="mt-4 text-center text-muted">Não tem uma conta? <a href="<?= h(url($basePath, 'pages/cadastro.php')) ?>" class="fw-semibold text-decoration-none">Cadastre-se</a></div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
