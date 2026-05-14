<?php
declare(strict_types=1);

$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

render_resource_list_page('alas', $basePath, 'alas', 'Alas', 'Cadastre e edite as alas usadas para organizar quartos e leitos.');
