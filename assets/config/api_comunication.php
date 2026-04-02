<?php

$apiBaseUrl = 'http://localhost:8081';

$entityConfigs = [
    'alas' => [
        'label' => 'Alas',
        'singular' => 'Ala',
        'endpoint' => '/api/alas',
        'primary_keys' => ['codala'],
        'summary' => 'Setores e andares do hospital.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome da Ala', 'type' => 'text', 'required' => true],
            ['name' => 'andar', 'label' => 'Andar', 'type' => 'number', 'required' => false],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codala'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'Andar', 'path' => 'andar'],
        ],
    ],
    'especialidades' => [
        'label' => 'Especialidades',
        'singular' => 'Especialidade',
        'endpoint' => '/api/especialidades',
        'primary_keys' => ['codespecialidade'],
        'summary' => 'Especialidades medicas.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'descricao', 'label' => 'Descricao', 'type' => 'text', 'required' => false],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codespecialidade'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'Descricao', 'path' => 'descricao'],
        ],
    ],
    'tipos-sanguineos' => [
        'label' => 'Tipos Sanguineos',
        'singular' => 'Tipo Sanguineo',
        'endpoint' => '/api/tipos-sanguineos',
        'primary_keys' => ['codtipo'],
        'summary' => 'Tipos sanguineos dos pacientes.',
        'fields' => [
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'text', 'required' => true],
            ['name' => 'fatorrh', 'label' => 'Fator RH', 'type' => 'text', 'required' => true],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codtipo'],
            ['label' => 'Tipo', 'path' => 'tipo'],
            ['label' => 'Fator RH', 'path' => 'fatorrh'],
        ],
    ],
    'medicos' => [
        'label' => 'Medicos',
        'singular' => 'Medico',
        'endpoint' => '/api/medicos',
        'primary_keys' => ['codmedico'],
        'summary' => 'Cadastro de medicos e especialidades.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'crm', 'label' => 'CRM', 'type' => 'text', 'required' => true],
            ['name' => 'telefone', 'label' => 'Telefone', 'type' => 'text', 'required' => false],
            [
                'name' => 'codespecialidade',
                'label' => 'Especialidade',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'especialidades',
                'option_value' => 'codespecialidade',
                'option_label' => 'nome',
                'source' => 'especialidade.codespecialidade',
            ],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codmedico'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'CRM', 'path' => 'crm'],
            ['label' => 'Telefone', 'path' => 'telefone'],
            ['label' => 'Especialidade', 'path' => 'especialidade.nome'],
        ],
    ],
    'pacientes' => [
        'label' => 'Pacientes',
        'singular' => 'Paciente',
        'endpoint' => '/api/pacientes',
        'primary_keys' => ['codpaciente'],
        'summary' => 'Cadastro de pacientes e tipo sanguineo.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'cpf', 'label' => 'CPF', 'type' => 'text', 'required' => true],
            ['name' => 'dataNascimento', 'label' => 'Data de Nascimento', 'type' => 'date', 'required' => true],
            [
                'name' => 'codtipo',
                'label' => 'Tipo Sanguineo',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'tipos-sanguineos',
                'option_value' => 'codtipo',
                'option_label' => 'tipo',
                'source' => 'tipoSanguineo.codtipo',
            ],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codpaciente'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'CPF', 'path' => 'cpf'],
            ['label' => 'Nascimento', 'path' => 'dataNascimento'],
            ['label' => 'Tipo Sanguineo', 'path' => 'tipoSanguineo.tipo'],
            ['label' => 'Fator RH', 'path' => 'tipoSanguineo.fatorrh'],
        ],
    ],
    'quartos' => [
        'label' => 'Quartos',
        'singular' => 'Quarto',
        'endpoint' => '/api/quartos',
        'primary_keys' => ['codquarto'],
        'summary' => 'Quartos vinculados a alas.',
        'fields' => [
            ['name' => 'numero', 'label' => 'Numero', 'type' => 'number', 'required' => true],
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'text', 'required' => false],
            [
                'name' => 'codala',
                'label' => 'Ala',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'alas',
                'option_value' => 'codala',
                'option_label' => 'nome',
                'source' => 'ala.codala',
            ],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codquarto'],
            ['label' => 'Numero', 'path' => 'numero'],
            ['label' => 'Tipo', 'path' => 'tipo'],
            ['label' => 'Ala', 'path' => 'ala.nome'],
        ],
    ],
    'leitos' => [
        'label' => 'Leitos',
        'singular' => 'Leito',
        'endpoint' => '/api/leitos',
        'primary_keys' => ['codleito'],
        'summary' => 'Leitos vinculados a quartos.',
        'fields' => [
            ['name' => 'status', 'label' => 'Status', 'type' => 'text', 'required' => true],
            [
                'name' => 'codquarto',
                'label' => 'Quarto',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'quartos',
                'option_value' => 'codquarto',
                'option_label' => 'numero',
                'source' => 'quarto.codquarto',
            ],
        ],
        'columns' => [
            ['label' => 'Codigo', 'path' => 'codleito'],
            ['label' => 'Status', 'path' => 'status'],
            ['label' => 'Quarto', 'path' => 'quarto.numero'],
            ['label' => 'Ala', 'path' => 'quarto.ala.nome'],
        ],
    ],
];

function apiRequest(string $method, string $endpoint, ?array $payload = null): array
{
    global $apiBaseUrl;

    $url = rtrim($apiBaseUrl, '/') . $endpoint;

    $ch = curl_init($url);

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($response === false) {
        return [
            'success' => false,
            'status' => $httpCode,
            'error' => $error ?: 'Erro desconhecido ao chamar a API.',
            'data' => null,
        ];
    }

    $decoded = json_decode($response, true);

    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'status' => $httpCode,
        'error' => $httpCode >= 400 ? ($decoded['message'] ?? $response) : null,
        'data' => $decoded,
    ];
}

function getNestedValue(array $data, string $path, mixed $default = null): mixed
{
    $segments = explode('.', $path);
    $value = $data;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function setNestedValue(array &$target, string $path, mixed $value): void
{
    $segments = explode('.', $path);
    $current = &$target;

    foreach ($segments as $index => $segment) {
        $last = $index === array_key_last($segments);

        if ($last) {
            $current[$segment] = $value;
            return;
        }

        if (!isset($current[$segment]) || !is_array($current[$segment])) {
            $current[$segment] = [];
        }

        $current = &$current[$segment];
    }
}

function buildPayloadFromForm(string $resourceKey, array $formData): array
{
    global $entityConfigs;

    $config = $entityConfigs[$resourceKey];
    $payload = [];

    foreach ($config['primary_keys'] as $primaryKey) {
        if (isset($formData[$primaryKey]) && $formData[$primaryKey] !== '') {
            $payload[$primaryKey] = is_numeric($formData[$primaryKey])
                ? (int) $formData[$primaryKey]
                : $formData[$primaryKey];
        }
    }

    foreach ($config['fields'] as $field) {
        $fieldName = $field['name'];
        $rawValue = $formData[$fieldName] ?? null;

        if ($rawValue === '' || $rawValue === null) {
            continue;
        }

        $value = is_numeric($rawValue) && !in_array($field['type'], ['text', 'email', 'date'], true)
            ? $rawValue + 0
            : $rawValue;

        if (!empty($field['source'])) {
            setNestedValue($payload, $field['source'], $value);
        } else {
            $payload[$fieldName] = $value;
        }
    }

    return $payload;
}

function fetchResourceList(string $resourceKey): array
{
    global $entityConfigs;

    return apiRequest('GET', $entityConfigs[$resourceKey]['endpoint']);
}

function fetchResourceById(string $resourceKey, int|string $id): array
{
    global $entityConfigs;

    return apiRequest('GET', $entityConfigs[$resourceKey]['endpoint'] . '/' . $id);
}

function saveResource(string $resourceKey, array $formData): array
{
    global $entityConfigs;

    $payload = buildPayloadFromForm($resourceKey, $formData);

    return apiRequest('POST', $entityConfigs[$resourceKey]['endpoint'], $payload);
}

function deleteResource(string $resourceKey, int|string $id): array
{
    global $entityConfigs;

    return apiRequest('DELETE', $entityConfigs[$resourceKey]['endpoint'] . '/' . $id);
}
