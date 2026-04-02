<?php
declare(strict_types=1);

$basePath = '.';
$erro = $erro ?? null;
$formAction = $formAction ?? '#';
require_once __DIR__ . '/includes/app.php';

render_head('HMS - Login', $basePath);
?>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-highlight d-flex flex-column justify-content-between">
            <div>
                <img src="<?= h(url($basePath, 'assets/images/logo.png')) ?>" alt="Logo HMS" class="brand-logo-image">
                <h1 class="display-5 fw-bold mt-4 mb-3">Acesso ao centro de gestao hospitalar.</h1>
                <p class="mb-0 text-white-50 fs-5">Entre para administrar pacientes, equipes medicas, especialidades, alas, quartos e leitos em um unico sistema.</p>
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
                    <p class="text-muted mb-4">Use suas credenciais para acessar o painel administrativo da instituicao.</p>
                    <form action="<?= h($formAction) ?>" method="post">
<?php if ($erro): ?>
                        <div class="alert alert-danger"><?= h($erro) ?></div>
<?php endif; ?>
                        <div class="mb-3"><label for="username" class="form-label">Usuario</label><input type="text" name="username" class="form-control" id="username" required></div>
                        <div class="mb-4"><label for="password" class="form-label">Senha</label><input type="password" name="password" class="form-control" id="password" required></div>
                        <button class="w-100 btn btn-primary btn-lg" type="submit">Entrar</button>
                        <div class="mt-4 text-center text-muted">Nao tem uma conta? <a href="<?= h(url($basePath, 'cadastro.php')) ?>" class="fw-semibold text-decoration-none">Cadastre-se</a></div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
