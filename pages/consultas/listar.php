<?php
declare(strict_types=1);

$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

render_resource_list_page('consultas', $basePath, 'consultas', 'Consultas', 'Gerencie consultas, data, paciente, médico e motivo.');
