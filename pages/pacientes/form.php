<?php
declare(strict_types=1);

$basePath = '../..';
require_once __DIR__ . '/../../includes/app.php';

require_authentication(url($basePath, 'pages/login.php'));

render_resource_form_page('pacientes', $basePath, 'pacientes', 'Novo paciente', 'Cadastre nome, nascimento e tipo sanguíneo do paciente.');
