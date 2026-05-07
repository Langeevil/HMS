<?php
declare(strict_types=1);

$basePath = '..';
$usuario = $usuario ?? [];
$formAction = $formAction ?? '#';
require_once __DIR__ . '/../includes/app.php';

render_head('HMS - Cadastro', $basePath);
?>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-highlight d-flex flex-column justify-content-between">
            <div>
                <img src="<?= h(url($basePath, 'assets/images/logo.png')) ?>" alt="Logo HMS" class="brand-logo-image">
                <h1 class="display-5 fw-bold mt-4 mb-3">Cadastre o acesso administrativo inicial.</h1>
                <p class="mb-0 text-white-50 fs-5">Prepare o ambiente para gerenciar profissionais, pacientes, setores hospitalares e recursos de internação.</p>
            </div>
            <div class="pt-4">
                <p class="mb-1 fw-semibold">Implantação do HMS</p>
                <small class="text-white-50">Comece a estruturar o controle administrativo da unidade desde o primeiro acesso.</small>
            </div>
        </section>
        <section class="auth-panel">
            <a href="<?= h(url($basePath, 'index.php')) ?>" class="auth-back d-inline-block mb-4">&larr; Voltar</a>
            <div class="auth-card card">
                <div class="card-body p-0">
                    <h2 class="h3 fw-bold mb-2">Criar nova conta</h2>
                    <p class="text-muted mb-4">Cadastre o primeiro usuário responsável pela administração do sistema.</p>
                    <form action="<?= h($formAction) ?>" method="post">
                        <div class="mb-3"><label for="nome" class="form-label">Nome completo</label><input type="text" name="nome" value="<?= h($usuario['nome'] ?? '') ?>" class="form-control" id="nome" required></div>
                        <div class="mb-3"><label for="username" class="form-label">Usuário</label><input type="text" name="username" value="<?= h($usuario['username'] ?? '') ?>" class="form-control" id="username" required></div>
                        <div class="mb-4"><label for="password" class="form-label">Senha</label><input type="password" name="password" class="form-control" id="password" required></div>
                        <button class="w-100 btn btn-success btn-lg" type="submit">Cadastrar</button>
                        <div class="mt-4 text-center text-muted">Já tem uma conta? <a href="<?= h(url($basePath, 'pages/login.php')) ?>" class="fw-semibold text-decoration-none">Entrar</a></div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
