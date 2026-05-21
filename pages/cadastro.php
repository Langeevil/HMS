<?php
declare(strict_types=1);

$basePath = '..';
require_once __DIR__ . '/../includes/app.php';

if (is_authenticated()) {
    header('Location: ' . url($basePath, 'pages/dashboard.php'));
    exit;
}

$usuario = [
    'nome' => '',
    'username' => '',
];
$erro = null;
$formAction = $formAction ?? url($basePath, 'pages/cadastro.php');

// Endpoint REST de cadastro da API HMS. Ajuste aqui se a rota real for diferente.
$registrationApiEndpoint = $registrationApiEndpoint ?? '/api/usuarios';

function cadastro_password_errors(string $password): array
{
    $rules = [
        [strlen($password) >= 8, 'A senha deve ter no minimo 8 caracteres.'],
        [preg_match('/[A-Z]/', $password) === 1, 'Inclua pelo menos uma letra maiuscula.'],
        [preg_match('/[a-z]/', $password) === 1, 'Inclua pelo menos uma letra minuscula.'],
        [preg_match('/\d/', $password) === 1, 'Inclua pelo menos um numero.'],
        [preg_match('/[^A-Za-z0-9\s]/', $password) === 1, 'Inclua pelo menos um caractere especial.'],
        [preg_match('/\s/', $password) !== 1, 'A senha nao pode conter espacos.'],
    ];

    return array_values(array_map(
        static fn (array $rule): string => $rule[1],
        array_filter($rules, static fn (array $rule): bool => $rule[0] === false)
    ));
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $usuario['nome'] = trim((string) ($_POST['nome'] ?? ''));
    $usuario['username'] = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    $passwordErrors = cadastro_password_errors($password);

    if ($usuario['nome'] === '' || $usuario['username'] === '') {
        $erro = 'Informe nome e usuario para criar a conta.';
    } elseif ($password === '' || $confirmPassword === '') {
        $erro = 'Informe e confirme a senha.';
    } elseif ($passwordErrors !== []) {
        $erro = 'A senha ainda nao atende aos requisitos: ' . implode(' ', $passwordErrors);
    } elseif ($password !== $confirmPassword) {
        $erro = 'As senhas informadas nao coincidem.';
    } else {
        $response = registerUser($usuario['nome'], $usuario['username'], $password, $registrationApiEndpoint);

        if ($response['success']) {
            set_flash('registration_success', 'Conta criada com sucesso. Entre com o usuario cadastrado.');
            header('Location: ' . url($basePath, 'pages/login.php'));
            exit;
        }

        $erro = $response['error'] ?: 'Nao foi possivel criar a conta na API HMS.';
    }
}

render_head('HMS - Cadastro', $basePath, true, ['assets/css/signup.css']);
?>
<body class="signup-page">
    <main class="signup-container">
        <section class="signup-intro" aria-label="Apresentacao do cadastro HMS">
            <div>
                <img src="<?= h(url($basePath, 'assets/images/HMS.png')) ?>" alt="Logo HMS" class="signup-logo">
                <div class="signup-tag">Cadastro HMS</div>
                <h1>Crie o acesso administrativo inicial.</h1>
                <p>Configure uma conta segura para acessar pacientes, medicos, leitos e demais rotinas administrativas do hospital.</p>
            </div>
            <div class="signup-note">
                <strong>Validacao em tempo real</strong>
                <span>A senha so sera enviada quando cumprir todos os requisitos e coincidir com a confirmacao.</span>
            </div>
        </section>

        <section class="signup-card" aria-labelledby="signup-title">
            <a href="<?= h(url($basePath, 'index.php')) ?>" class="signup-back">Voltar</a>

            <div class="signup-header">
                <div class="lock-box" id="lockBox" aria-hidden="true">
                    <i class="bi bi-unlock-fill" id="lockIcon"></i>
                </div>
                <div>
                    <h2 id="signup-title">Criar conta</h2>
                    <p>Informe os dados e escolha uma senha forte.</p>
                </div>
            </div>

            <form action="<?= h($formAction) ?>" method="post" id="signupForm" data-password-form novalidate>
<?php if ($erro): ?>
                <div class="signup-alert error" role="alert"><?= h($erro) ?></div>
<?php endif; ?>
                <div class="signup-alert" id="formValidationMessage" role="alert" aria-live="polite"></div>

                <div class="signup-field">
                    <label for="nome">Nome</label>
                    <div class="input-wrap">
                        <input type="text" name="nome" id="nome" value="<?= h($usuario['nome']) ?>" placeholder="Seu nome" class="normal-field" autocomplete="name" required>
                    </div>
                </div>

                <div class="signup-field">
                    <label for="username">Usuario</label>
                    <div class="input-wrap">
                        <input type="text" name="username" id="username" value="<?= h($usuario['username']) ?>" placeholder="usuario.hms" class="normal-field" autocomplete="username" required>
                    </div>
                </div>

                <div class="signup-field password-field">
                    <label for="password">Senha</label>
                    <div class="input-wrap" id="passwordWrap">
                        <input type="password" name="password" id="password" placeholder="Crie uma senha" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar senha">Mostrar</button>
                    </div>

                    <div class="strength-row">
                        <span id="strengthText" class="strength-text weak">Digite uma senha segura</span>
                        <span id="strengthCount">0/6</span>
                    </div>

                    <div class="strength-bar" aria-hidden="true">
                        <div class="bar-fill" id="barFill"></div>
                    </div>

                    <div class="password-bubble" id="passwordBubble">
                        <div class="bubble-head">
                            <div>
                                <div class="bubble-title" id="bubbleTitle">Forca da senha</div>
                                <div class="bubble-sub" id="bubbleSub">0 de 6 requisitos</div>
                            </div>
                            <i class="bi bi-shield-lock" aria-hidden="true"></i>
                        </div>

                        <div class="strength-bar bubble-bar" aria-hidden="true">
                            <div class="bar-fill" id="bubbleBarFill"></div>
                        </div>

                        <div class="rule" id="ruleLength">
                            <span class="rule-icon">x</span>
                            Minimo de 8 caracteres
                        </div>
                        <div class="rule" id="ruleUpper">
                            <span class="rule-icon">x</span>
                            Uma letra maiuscula
                        </div>
                        <div class="rule" id="ruleLower">
                            <span class="rule-icon">x</span>
                            Uma letra minuscula
                        </div>
                        <div class="rule" id="ruleNumber">
                            <span class="rule-icon">x</span>
                            Um numero
                        </div>
                        <div class="rule" id="ruleSpecial">
                            <span class="rule-icon">x</span>
                            Um caractere especial
                        </div>
                        <div class="rule" id="ruleNoSpaces">
                            <span class="rule-icon">x</span>
                            Sem espacos
                        </div>
                    </div>
                </div>

                <div class="connector" id="connector" aria-hidden="true"></div>

                <div class="signup-field">
                    <label for="confirmPassword">Repetir senha</label>
                    <div class="input-wrap" id="confirmWrap">
                        <input type="password" name="confirm_password" id="confirmPassword" placeholder="Repita sua senha" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" data-target="confirmPassword" aria-label="Mostrar confirmacao de senha">Mostrar</button>
                    </div>

                    <div class="confirm-msg" id="confirmMsg" aria-live="polite"></div>
                </div>

                <button class="signup-submit" type="submit" id="submitBtn" disabled>Criar conta</button>
                <div class="signup-login">Ja tem uma conta? <a href="<?= h(url($basePath, 'pages/login.php')) ?>">Entrar</a></div>
            </form>
        </section>
    </main>

    <script src="<?= h(url($basePath, 'assets/js/signup-password.js')) ?>"></script>
</body>
</html>
