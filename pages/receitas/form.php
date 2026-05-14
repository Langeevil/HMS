<?php
declare(strict_types=1);

$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

render_resource_form_page('receitas', $basePath, 'receitas', 'Nova receita', 'Informe validade e consulta relacionada.');
