<?php

declare(strict_types=1);

$apiBaseUrl = 'http://localhost:8081';

$authConfig = [
    'login' => [
        'attempts' => [
            [
                'endpoint' => '/login',
                'content_type' => 'application/x-www-form-urlencoded',
                'accept' => 'application/json, text/plain, */*',
                'success_redirect_contains' => '/dashboard',
            ],
        ],
    ],
];

$entityConfigs = [
    'alas' => [
        'label' => 'Alas',
        'singular' => 'Ala',
        'endpoint' => '/api/alas',
        'primary_keys' => ['codala'],
        'summary' => 'Alas usadas para organizar quartos e leitos.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome da ala', 'type' => 'text', 'required' => true],
            ['name' => 'andar', 'label' => 'Andar', 'type' => 'number', 'required' => false],
        ],
        'columns' => [
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'Andar', 'path' => 'andar'],
        ],
    ],
    'tipos-sanguineos' => [
        'label' => 'Tipos sanguíneos',
        'singular' => 'Tipo sanguíneo',
        'endpoint' => '/api/tipos-sanguineos',
        'primary_keys' => ['codtipo'],
        'summary' => 'Tipos sanguíneos usados no cadastro de pacientes.',
        'fields' => [
            ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'text', 'required' => true],
            ['name' => 'fatorrh', 'label' => 'Fator RH', 'type' => 'text', 'required' => true],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codtipo'],
            ['label' => 'Tipo', 'path' => 'tipo'],
            ['label' => 'Fator RH', 'path' => 'fatorrh'],
        ],
    ],
    'pacientes' => [
        'label' => 'Pacientes',
        'singular' => 'Paciente',
        'endpoint' => '/api/pacientes',
        'primary_keys' => ['codpaciente'],
        'summary' => 'Pacientes e dados básicos do prontuário.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'datanasc', 'label' => 'Data de nascimento', 'type' => 'date', 'required' => true],
            [
                'name' => 'codtipodk',
                'label' => 'Tipo sanguíneo',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'tipos-sanguineos',
                'option_value' => 'codtipo',
                'option_label_fields' => ['tipo', 'fatorrh'],
            ],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codpaciente'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'Nascimento', 'path' => 'datanasc'],
            ['label' => 'Tipo sanguíneo', 'path' => 'tipoSanguineo.tipo', 'fallback_paths' => ['codtipodk']],
        ],
    ],
    'especialidades' => [
        'label' => 'Especialidades',
        'singular' => 'Especialidade',
        'endpoint' => '/api/especialidades',
        'primary_keys' => ['codespecialidade'],
        'summary' => 'Especialidades usadas no cadastro de médicos.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'descricao', 'label' => 'Descrição', 'type' => 'textarea', 'required' => false],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codespecialidade'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'Descrição', 'path' => 'descricao'],
        ],
    ],
    'medicos' => [
        'label' => 'Médicos',
        'singular' => 'Médico',
        'endpoint' => '/api/medicos',
        'primary_keys' => ['codmedico'],
        'summary' => 'Médicos vinculados às especialidades.',
        'fields' => [
            ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ['name' => 'crm', 'label' => 'CRM', 'type' => 'text', 'required' => true],
            [
                'name' => 'codespecialidade',
                'label' => 'Especialidade',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'especialidades',
                'option_value' => 'codespecialidade',
                'option_label' => 'nome',
            ],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codmedico'],
            ['label' => 'Nome', 'path' => 'nome'],
            ['label' => 'CRM', 'path' => 'crm'],
            ['label' => 'Especialidade', 'path' => 'especialidade.nome', 'fallback_paths' => ['codespecialidade']],
        ],
    ],
    'quartos' => [
        'label' => 'Quartos',
        'singular' => 'Quarto',
        'endpoint' => '/api/quartos',
        'primary_keys' => ['codquarto'],
        'summary' => 'Quartos vinculados às alas.',
        'fields' => [
            ['name' => 'numero', 'label' => 'Número', 'type' => 'number', 'required' => true],
            [
                'name' => 'tipo',
                'label' => 'Tipo',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'privativo', 'label' => 'Privativo (1 leito)'],
                    ['value' => 'semiprivativo', 'label' => 'Semiprivativo (2 leitos)'],
                    ['value' => 'enfermaria', 'label' => 'Enfermaria (6 leitos)'],
                ],
            ],
            [
                'name' => 'codala',
                'label' => 'Ala',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'alas',
                'option_value' => 'codala',
                'option_label' => 'nome',
            ],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codquarto'],
            ['label' => 'Número', 'path' => 'numero'],
            ['label' => 'Tipo', 'path' => 'tipo'],
            ['label' => 'Ala', 'path' => 'ala.nome', 'fallback_paths' => ['codala']],
        ],
    ],
    'leitos' => [
        'label' => 'Leitos',
        'singular' => 'Leito',
        'endpoint' => '/api/leitos',
        'available_endpoint' => '/api/leitos/disponiveis',
        'primary_keys' => ['codleito'],
        'summary' => 'Leitos vinculados aos quartos.',
        'fields' => [
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'livre', 'label' => 'Livre'],
                    ['value' => 'manutencao', 'label' => 'Manutenção'],
                ],
            ],
            [
                'name' => 'codquarto',
                'label' => 'Quarto',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'quartos',
                'option_value' => 'codquarto',
                'option_label_fields' => ['numero', 'tipo'],
            ],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codleito'],
            ['label' => 'Quarto', 'path' => 'quarto.numero', 'fallback_paths' => ['codquarto']],
            ['label' => 'Ala', 'path' => 'quarto.ala.nome'],
            ['label' => 'Status', 'path' => 'status'],
        ],
    ],
    'consultas' => [
        'label' => 'Consultas',
        'singular' => 'Consulta',
        'endpoint' => '/api/consultas',
        'primary_keys' => ['codconsulta'],
        'summary' => 'Consultas vinculadas a pacientes e médicos.',
        'fields' => [
            ['name' => 'datahora', 'label' => 'Data e hora', 'type' => 'datetime-local', 'required' => true],
            ['name' => 'motivo', 'label' => 'Motivo', 'type' => 'textarea', 'required' => true],
            [
                'name' => 'codpacientefk',
                'label' => 'Paciente',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'pacientes',
                'option_value' => 'codpaciente',
                'option_label' => 'nome',
            ],
            [
                'name' => 'codmedicofk',
                'label' => 'Médico',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'medicos',
                'option_value' => 'codmedico',
                'option_label_fields' => ['nome', 'crm'],
            ],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codconsulta'],
            ['label' => 'Data e hora', 'path' => 'datahora'],
            ['label' => 'Motivo', 'path' => 'motivo'],
            ['label' => 'Paciente', 'path' => 'paciente.nome', 'fallback_paths' => ['codpacientefk']],
            ['label' => 'Médico', 'path' => 'medico.nome', 'fallback_paths' => ['codmedicofk']],
        ],
    ],
    'receitas' => [
        'label' => 'Receitas',
        'singular' => 'Receita',
        'endpoint' => '/api/receitas',
        'primary_keys' => ['codreceita'],
        'summary' => 'Receitas emitidas a partir de consultas.',
        'fields' => [
            ['name' => 'validade', 'label' => 'Validade', 'type' => 'date', 'required' => true],
            [
                'name' => 'codconsultafk',
                'label' => 'Consulta',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'consultas',
                'option_value' => 'codconsulta',
                'option_label_fields' => ['codconsulta', 'datahora'],
            ],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codreceita'],
            ['label' => 'Validade', 'path' => 'validade'],
            ['label' => 'Consulta', 'path' => 'consulta.codconsulta', 'fallback_paths' => ['codconsultafk']],
        ],
    ],
    'medicamentos' => [
        'label' => 'Medicamentos',
        'singular' => 'Medicamento',
        'endpoint' => '/api/medicamentos',
        'primary_keys' => ['codmedicamento'],
        'summary' => 'Medicamentos usados em receitas.',
        'fields' => [
            ['name' => 'nomegenerico', 'label' => 'Nome genérico', 'type' => 'text', 'required' => true],
            ['name' => 'laboratorio', 'label' => 'Laboratório', 'type' => 'text', 'required' => true],
        ],
        'columns' => [
            ['label' => 'Código', 'path' => 'codmedicamento'],
            ['label' => 'Nome genérico', 'path' => 'nomegenerico'],
            ['label' => 'Laboratório', 'path' => 'laboratorio'],
        ],
    ],
    'exames-consulta' => [
        'label' => 'Exames da consulta',
        'singular' => 'Exame da consulta',
        'endpoint' => '/api/exames-consulta',
        'primary_keys' => ['codconsultafk', 'codexamefk'],
        'summary' => 'Resultados de exames vinculados a consultas.',
        'fields' => [
            [
                'name' => 'codconsultafk',
                'label' => 'Consulta',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'consultas',
                'option_value' => 'codconsulta',
                'option_label_fields' => ['codconsulta', 'datahora'],
            ],
            ['name' => 'codexamefk', 'label' => 'Código do exame', 'type' => 'number', 'required' => true],
            ['name' => 'resultadourl', 'label' => 'URL do resultado', 'type' => 'url', 'required' => false],
            ['name' => 'datarealizacao', 'label' => 'Data de realização', 'type' => 'date', 'required' => true],
        ],
        'columns' => [
            ['label' => 'Consulta', 'path' => 'consulta.codconsulta', 'fallback_paths' => ['codconsultafk']],
            ['label' => 'Exame', 'path' => 'exame.codexame', 'fallback_paths' => ['codexamefk']],
            ['label' => 'Resultado', 'path' => 'resultadourl'],
            ['label' => 'Realização', 'path' => 'datarealizacao'],
        ],
    ],
    'itens-receita' => [
        'label' => 'Itens da receita',
        'singular' => 'Item da receita',
        'endpoint' => '/api/itens-receita',
        'primary_keys' => ['codreceitafk', 'codmedicamentofk'],
        'summary' => 'Medicamentos e posologia de cada receita.',
        'fields' => [
            [
                'name' => 'codreceitafk',
                'label' => 'Receita',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'receitas',
                'option_value' => 'codreceita',
                'option_label_fields' => ['codreceita', 'validade'],
            ],
            [
                'name' => 'codmedicamentofk',
                'label' => 'Medicamento',
                'type' => 'select',
                'required' => true,
                'options_resource' => 'medicamentos',
                'option_value' => 'codmedicamento',
                'option_label_fields' => ['nomegenerico', 'laboratorio'],
            ],
            ['name' => 'posologia', 'label' => 'Posologia', 'type' => 'textarea', 'required' => true],
        ],
        'columns' => [
            ['label' => 'Receita', 'path' => 'receita.codreceita', 'fallback_paths' => ['codreceitafk']],
            ['label' => 'Medicamento', 'path' => 'medicamento.nomegenerico', 'fallback_paths' => ['codmedicamentofk']],
            ['label' => 'Posologia', 'path' => 'posologia'],
        ],
    ],
];

function apiBuildUrl(string $endpoint, array $query = []): string
{
    global $apiBaseUrl;

    $url = rtrim($apiBaseUrl, '/') . $endpoint;

    if ($query !== []) {
        $queryString = http_build_query($query);
        $url .= str_contains($url, '?') ? '&' . $queryString : '?' . $queryString;
    }

    return $url;
}

function apiBuildHeaders(array $options = []): array
{
    $contentType = $options['content_type'] ?? 'application/json';
    $accept = $options['accept'] ?? 'application/json';
    $headers = ['Accept: ' . $accept];

    if ($contentType !== '') {
        $headers[] = 'Content-Type: ' . $contentType;
    }

    if (!empty($options['headers']) && is_array($options['headers'])) {
        $headers = array_merge($headers, $options['headers']);
    }

    return $headers;
}

function apiEncodePayload(?array $payload, string $contentType): ?string
{
    if ($payload === null) {
        return null;
    }

    return match ($contentType) {
        'application/x-www-form-urlencoded' => http_build_query($payload),
        default => json_encode($payload, JSON_UNESCAPED_UNICODE),
    };
}

function apiNormalizeResponse(bool|string $rawBody, int $httpCode, string $curlError, array $responseHeaders = []): array
{
    if ($rawBody === false) {
        return [
            'success' => false,
            'status' => $httpCode,
            'error' => $curlError !== '' ? $curlError : 'Erro desconhecido ao chamar a API.',
            'data' => null,
            'headers' => $responseHeaders,
        ];
    }

    $decoded = json_decode($rawBody, true);
    $data = json_last_error() === JSON_ERROR_NONE ? $decoded : $rawBody;
    $errorMessage = null;

    if ($httpCode >= 400) {
        $errorMessage = is_array($decoded)
            ? ($decoded['erro'] ?? $decoded['error'] ?? $decoded['message'] ?? $rawBody)
            : $rawBody;

        if ($errorMessage === '' || $errorMessage === null) {
            $errorMessage = 'A API respondeu com erro HTTP ' . $httpCode . '.';
        }

        $statusMessages = [
            400 => 'Dados inválidos',
            404 => 'Registro não encontrado',
            409 => 'Operação bloqueada por regra de negócio',
        ];

        if (isset($statusMessages[$httpCode])) {
            $errorMessage = $statusMessages[$httpCode] . ': ' . $errorMessage;
        }
    }

    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'status' => $httpCode,
        'error' => $errorMessage,
        'data' => $data,
        'headers' => $responseHeaders,
    ];
}

function apiGetHeaderValue(array $headers, string $name): string|array|null
{
    return $headers[strtolower($name)] ?? null;
}

function apiRequest(string $method, string $endpoint, ?array $payload = null, array $options = []): array
{
    $query = $options['query'] ?? [];
    $contentType = $options['content_type'] ?? 'application/json';
    $url = apiBuildUrl($endpoint, is_array($query) ? $query : []);
    $headers = apiBuildHeaders($options);
    $responseHeaders = [];
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT_MS => (int) ($options['connect_timeout_ms'] ?? 800),
        CURLOPT_TIMEOUT_MS => (int) (($options['timeout_ms'] ?? (($options['timeout'] ?? 5) * 1000))),
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_FOLLOWLOCATION => (bool) ($options['follow_redirects'] ?? false),
        CURLOPT_HEADERFUNCTION => static function ($curlHandle, string $headerLine) use (&$responseHeaders): int {
            $trimmed = trim($headerLine);
            $length = strlen($headerLine);

            if ($trimmed === '' || !str_contains($trimmed, ':')) {
                return $length;
            }

            [$name, $value] = explode(':', $trimmed, 2);
            $normalizedName = strtolower(trim($name));
            $normalizedValue = trim($value);

            if (isset($responseHeaders[$normalizedName])) {
                if (!is_array($responseHeaders[$normalizedName])) {
                    $responseHeaders[$normalizedName] = [$responseHeaders[$normalizedName]];
                }
                $responseHeaders[$normalizedName][] = $normalizedValue;
            } else {
                $responseHeaders[$normalizedName] = $normalizedValue;
            }

            return $length;
        },
    ]);

    $body = apiEncodePayload($payload, $contentType);
    if ($body !== null && !in_array(strtoupper($method), ['GET', 'DELETE'], true)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    $rawBody = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    return apiNormalizeResponse($rawBody, $httpCode, $curlError, $responseHeaders);
}

function authenticateUser(string $username, string $password): array
{
    global $authConfig;

    $credentials = [
        'username' => $username,
        'password' => $password,
    ];
    $attemptErrors = [];
    $bestFailure = null;

    foreach ($authConfig['login']['attempts'] as $attempt) {
        $query = !empty($attempt['send_as_query']) ? $credentials : [];
        $payload = !empty($attempt['send_as_query']) ? null : $credentials;
        $response = apiRequest('POST', $attempt['endpoint'], $payload, [
            'content_type' => $attempt['content_type'] ?? 'application/json',
            'accept' => $attempt['accept'] ?? 'application/json',
            'query' => $query,
            'follow_redirects' => $attempt['follow_redirects'] ?? false,
            'timeout' => $attempt['timeout'] ?? 10,
        ]);

        $locationHeader = apiGetHeaderValue($response['headers'] ?? [], 'location');
        $location = is_array($locationHeader) ? (string) end($locationHeader) : (string) ($locationHeader ?? '');
        $redirectSuccess = $location !== ''
            && !empty($attempt['success_redirect_contains'])
            && str_contains($location, (string) $attempt['success_redirect_contains']);

        if ($response['success'] || $redirectSuccess) {
            if ($redirectSuccess && !is_array($response['data'])) {
                $response['data'] = [
                    'authenticated' => true,
                    'user' => ['username' => $username],
                ];
            }

            $response['success'] = true;
            $response['auth_attempt'] = $attempt;
            $response['debug_attempts'] = $attemptErrors;
            return $response;
        }

        $status = (int) ($response['status'] ?? 0);
        if ($bestFailure === null || $status >= 500 || ($status >= 400 && ($bestFailure['status'] ?? 0) < 500)) {
            $bestFailure = [
                'status' => $status,
                'endpoint' => $attempt['endpoint'],
                'error' => $response['error'] ?? null,
            ];
        }

        $attemptErrors[] = sprintf(
            'POST %s -> HTTP %d%s',
            $attempt['endpoint'],
            (int) ($response['status'] ?? 0),
            !empty($response['error']) ? ' (' . $response['error'] . ')' : ''
        );
    }

    return [
        'success' => false,
        'status' => (int) ($bestFailure['status'] ?? 0),
        'error' => $bestFailure !== null
            ? sprintf(
                'Falha ao autenticar na API. %s respondeu HTTP %d%s',
                $bestFailure['endpoint'],
                $bestFailure['status'],
                !empty($bestFailure['error']) ? ' (' . $bestFailure['error'] . ')' : ''
            )
            : 'Falha ao autenticar na API.',
        'data' => null,
        'headers' => [],
        'debug_attempts' => $attemptErrors,
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

function resourceEndpoint(string $resourceKey, int|string|array|null $id = null): string
{
    global $entityConfigs;

    $endpoint = $entityConfigs[$resourceKey]['endpoint'];
    if ($id === null || $id === '') {
        return $endpoint;
    }

    $segments = is_array($id) ? $id : [$id];
    foreach ($segments as $segment) {
        $endpoint .= '/' . rawurlencode((string) $segment);
    }

    return $endpoint;
}

function resourceIdFromData(string $resourceKey, array $data): string|array|null
{
    global $entityConfigs;

    $keys = $entityConfigs[$resourceKey]['primary_keys'] ?? [];
    $values = [];

    foreach ($keys as $key) {
        $value = $data[$key] ?? getNestedValue($data, $key);
        if ($value === null || $value === '') {
            return null;
        }
        $values[] = $value;
    }

    if ($values === []) {
        return null;
    }

    return count($values) === 1 ? (string) $values[0] : $values;
}

function buildPayloadFromForm(string $resourceKey, array $formData): array
{
    global $entityConfigs;

    $config = $entityConfigs[$resourceKey];
    $payload = [];

    foreach ($config['fields'] as $field) {
        $fieldName = $field['name'];
        $rawValue = $formData[$fieldName] ?? null;

        if ($rawValue === '' || $rawValue === null) {
            continue;
        }

        $type = $field['type'] ?? 'text';
        $value = is_numeric($rawValue) && !in_array($type, ['text', 'textarea', 'email', 'date', 'datetime-local', 'url'], true)
            ? $rawValue + 0
            : trim((string) $rawValue);

        if ($type === 'datetime-local' && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', (string) $value) === 1) {
            $value .= ':00';
        }

        $payload[$fieldName] = $value;
    }

    return $payload;
}

function fetchResourceList(string $resourceKey): array
{
    global $entityConfigs;
    static $requestCache = [];

    if (isset($requestCache[$resourceKey])) {
        return $requestCache[$resourceKey];
    }

    $requestCache[$resourceKey] = apiRequest('GET', $entityConfigs[$resourceKey]['endpoint']);

    return $requestCache[$resourceKey];
}

function fetchAvailableLeitos(): array
{
    global $entityConfigs;

    return apiRequest('GET', $entityConfigs['leitos']['available_endpoint']);
}

function fetchResourceById(string $resourceKey, int|string|array $id): array
{
    return apiRequest('GET', resourceEndpoint($resourceKey, $id));
}

function saveResource(string $resourceKey, array $formData): array
{
    $payload = buildPayloadFromForm($resourceKey, $formData);

    return apiRequest('POST', resourceEndpoint($resourceKey), $payload);
}

function updateResource(string $resourceKey, int|string|array $id, array $formData): array
{
    global $entityConfigs;

    $payload = buildPayloadFromForm($resourceKey, $formData);
    $primaryKeys = $entityConfigs[$resourceKey]['primary_keys'] ?? [];

    if (count($primaryKeys) === 1) {
        unset($payload[$primaryKeys[0]]);
    }

    return apiRequest('PUT', resourceEndpoint($resourceKey, $id), $payload);
}

function deleteResource(string $resourceKey, int|string|array $id): array
{
    return apiRequest('DELETE', resourceEndpoint($resourceKey, $id));
}

function occupyLeito(int|string $id): array
{
    return apiRequest('PATCH', resourceEndpoint('leitos', $id) . '/ocupar');
}

function releaseLeito(int|string $id): array
{
    return apiRequest('PATCH', resourceEndpoint('leitos', $id) . '/liberar');
}
