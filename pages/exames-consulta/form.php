<?php
declare(strict_types=1);

$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

set_flash('success', 'Cadastre exames e resultados pela consulta selecionada.');
header('Location: ' . url($basePath, 'pages/consultas/listar.php'));
exit;
