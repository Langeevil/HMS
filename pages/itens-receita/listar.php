<?php
declare(strict_types=1);

$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

set_flash('success', 'Itens da receita devem ser acessados pela receita selecionada.');
header('Location: ' . url($basePath, 'pages/receitas/listar.php'));
exit;
