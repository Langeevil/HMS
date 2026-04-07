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
        'application/json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
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
        $errorMessage = is_array($decoded) ? ($decoded['message'] ?? $rawBody) : $rawBody;

        if ($errorMessage === '' || $errorMessage === null) {
            $errorMessage = 'A API respondeu com erro HTTP ' . $httpCode . '.';
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
    $normalized = strtolower($name);
    return $headers[$normalized] ?? null;
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
        CURLOPT_TIMEOUT => (int) ($options['timeout'] ?? 10),
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
                    'user' => [
                        'username' => $username,
                    ],
                ];
            }

            $response['success'] = true;
            $response['auth_attempt'] = $attempt;
            $response['debug_attempts'] = $attemptErrors;
            return $response;
        }

        $status = (int) ($response['status'] ?? 0);
        if (
            $bestFailure === null
            || $status >= 500
            || ($status >= 400 && ($bestFailure['status'] ?? 0) < 500)
        ) {
            $bestFailure = [
                'status' => $status,
                'endpoint' => $attempt['endpoint'],
                'error' => $response['error'] ?? null,
            ];
        }

        $attemptErrors[] = sprintf(
            '%s %s -> HTTP %d%s',
            'POST',
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
