<?php

namespace App\Application\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConsultaExternaService
{
    private string $usuario;
    private string $password;
    private string $baseUrl;
    private PersonaService $personaService;
    private PersonaJuridicaService $personaJuridicaService;

    public function __construct(PersonaService $personaService, PersonaJuridicaService $personaJuridicaService)
    {
        $this->usuario = config('services.consulta_externa.usuario', '10447915125');
        $this->password = config('services.consulta_externa.password', '985511933');
        $this->baseUrl = config('services.consulta_externa.base_url', 'https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php');
        $this->personaService = $personaService;
        $this->personaJuridicaService = $personaJuridicaService;
    }

    public function consultarDni(string $dni): array
    {
        // Primero buscar en la tabla persona (caché de consultas)
        $persona = $this->personaService->findByDocumento($dni, '1'); // 1 = DNI
        if ($persona) {
            Log::info('DNI encontrado en tabla persona', ['dni' => $dni]);
            // Obtener datos completos o construir desde la entidad
            $datosCompletos = $persona->getDatosCompletos();
            if (!$datosCompletos) {
                $datosCompletos = [
                    'nombres' => $persona->getNombres(),
                    'apellidoPaterno' => $persona->getApellidoPaterno(),
                    'apellidoMaterno' => $persona->getApellidoMaterno(),
                    'nombreCompleto' => $persona->getNombreCompleto(),
                    'tipoDocumento' => '1',
                    'numeroDocumento' => $persona->getNumeroDocumento(),
                    'digitoVerificador' => $persona->getDigitoVerificador(),
                ];
            }
            // Aplanar la respuesta: devolver campos directamente
            return array_merge($datosCompletos, ['desde_bd' => true]);
        }

        // Si no está en BD, consultar API
        Log::info('DNI no encontrado en tabla persona, consultando API', ['dni' => $dni]);
        
        // Intentar primero con apis.net.pe (API funcional)
        $apisNetPeToken = config('services.consulta_externa.apis_net_pe_token');
        if ($apisNetPeToken) {
            $result = $this->consultarDniApisNetPe($dni, $apisNetPeToken);
            if ($result && !isset($result['correcto'])) {
                // Guardar en tabla persona
                $this->guardarPersonaDesdeApi($dni, '1', $result);
                return $result;
            }
        }
        
        // Intentar con APIs de pago si están configuradas
        $jsonPeToken = config('services.consulta_externa.json_pe_token');
        if ($jsonPeToken) {
            $result = $this->consultarDniJsonPe($dni, $jsonPeToken);
            if ($result && !isset($result['correcto'])) {
                $this->guardarPersonaDesdeApi($dni, '1', $result);
                return $result;
            }
        }
        
        $apiperuToken = config('services.consulta_externa.apiperu_token');
        if ($apiperuToken) {
            $result = $this->consultarDniApiPeru($dni, $apiperuToken);
            if ($result && !isset($result['correcto'])) {
                $this->guardarPersonaDesdeApi($dni, '1', $result);
                return $result;
            }
        }
        
        // Intentar con la API principal (puede estar bloqueada)
        $result = $this->consultarDniPrincipal($dni);
        if ($result && !isset($result['correcto'])) {
            $this->guardarPersonaDesdeApi($dni, '1', $result);
            return $result;
        }
        
        // Si falla, intentar con APIs alternativas gratuitas
        Log::info('API principal falló, intentando APIs alternativas', ['dni' => $dni]);
        
        // Intentar con api.reniec.cloud
        $result = $this->consultarDniReniecCloud($dni);
        if ($result && !isset($result['correcto'])) {
            $this->guardarPersonaDesdeApi($dni, '1', $result);
            return $result;
        }
        
        // Intentar con searchpe.herokuapp.com
        $result = $this->consultarDniSearchPe($dni);
        if ($result && !isset($result['correcto'])) {
            $this->guardarPersonaDesdeApi($dni, '1', $result);
            return $result;
        }
        
        return null; // Retornar null en caso de error
    }

    /**
     * Guarda una persona en la tabla persona desde los datos de la API
     * Esta tabla actúa como caché para evitar consultas duplicadas a las APIs externas
     */
    private function guardarPersonaDesdeApi(string $numeroDocumento, string $tipoDocumento, array $apiData): void
    {
        try {
            $datosCompletos = $apiData['datos_completos'] ?? $apiData;
            
            if ($tipoDocumento === '1') {
                // DNI
                $data = [
                    'numero_documento' => $numeroDocumento,
                    'tipo_documento' => '1',
                    'nombres' => $apiData['nombres'] ?? $datosCompletos['nombres'] ?? null,
                    'apellido_paterno' => $apiData['apellido_paterno'] ?? $datosCompletos['apellidoPaterno'] ?? null,
                    'apellido_materno' => $apiData['apellido_materno'] ?? $datosCompletos['apellidoMaterno'] ?? null,
                    'nombre_completo' => $apiData['nombre_completo'] ?? $datosCompletos['nombreCompleto'] ?? null,
                    'digito_verificador' => $apiData['digito_verificador'] ?? $datosCompletos['digitoVerificador'] ?? null,
                    'direccion' => $apiData['direccion'] ?? null,
                    'datos_completos' => $datosCompletos,
                ];
            } else {
                // RUC
                $dataApi = $apiData['data'] ?? $apiData;
                $data = [
                    'numero_documento' => $numeroDocumento,
                    'tipo_documento' => '6',
                    'razon_social' => $dataApi['razonSocial'] ?? $dataApi['razon_social'] ?? null,
                    'direccion' => $dataApi['direccion'] ?? null,
                    'ubigeo' => $dataApi['ubigeo'] ?? null,
                    'distrito' => $dataApi['distrito'] ?? null,
                    'provincia' => $dataApi['provincia'] ?? null,
                    'departamento' => $dataApi['departamento'] ?? null,
                    'estado' => $dataApi['estado'] ?? null,
                    'condicion' => $dataApi['condicion'] ?? null,
                    'es_agente_retencion' => $dataApi['EsAgenteRetencion'] ?? $dataApi['es_agente_retencion'] ?? false,
                    'es_buen_contribuyente' => $dataApi['EsBuenContribuyente'] ?? $dataApi['es_buen_contribuyente'] ?? false,
                    'datos_completos' => $dataApi,
                ];
            }
            
            // Verificar si ya existe
            $existente = $this->personaService->findByDocumento($numeroDocumento, $tipoDocumento);
            if ($existente) {
                // Actualizar solo si hay datos nuevos (no nulos)
                $dataToUpdate = array_filter($data, fn($v) => $v !== null && $v !== '');
                if (!empty($dataToUpdate)) {
                    $this->personaService->update($numeroDocumento, $dataToUpdate);
                    Log::info('Persona actualizada en tabla persona', [
                        'documento' => $numeroDocumento,
                        'tipo' => $tipoDocumento
                    ]);
                }
            } else {
                // Crear nueva
                $this->personaService->create($data);
                Log::info('Persona guardada en tabla persona', [
                    'documento' => $numeroDocumento,
                    'tipo' => $tipoDocumento
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error guardando persona desde API: ' . $e->getMessage(), [
                'documento' => $numeroDocumento,
                'tipo' => $tipoDocumento,
                'exception' => $e
            ]);
        }
    }

    private function consultarDniPrincipal(string $dni): array
    {
        try {
            $url = $this->baseUrl . "?documento=DNI&usuario={$this->usuario}&password={$this->password}&nro_documento={$dni}";
            
            // Primera petición - simular navegador para evitar bloqueo anti-bot
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                    'Referer' => 'https://www.facturacionelectronica.us/',
                    'Origin' => 'https://www.facturacionelectronica.us'
                ])
                ->withOptions([
                    'allow_redirects' => [
                        'max' => 5,
                        'strict' => true,
                        'referer' => true,
                        'protocols' => ['http', 'https'],
                        'track_redirects' => true
                    ],
                    'verify' => true
                ])
                ->get($url);
            
            $body = $response->body();
            
            // Si la respuesta es HTML con redirect JavaScript, extraer la URL del redirect
            if (str_contains($body, 'window.location.replace')) {
                preg_match("/window\.location\.replace\('([^']+)'\)/", $body, $matches);
                if (isset($matches[1])) {
                    $redirectUrl = $matches[1];
                    Log::info('API redirige, siguiendo redirect', ['url' => $redirectUrl]);
                    
                    // Hacer la petición al URL del redirect con headers de navegador
                    $response = Http::timeout(30)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                            'Accept' => 'application/json, text/plain, */*',
                            'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                            'Referer' => 'https://www.facturacionelectronica.us/',
                            'Origin' => 'https://www.facturacionelectronica.us'
                        ])
                        ->withOptions([
                            'verify' => true,
                            'allow_redirects' => [
                                'max' => 5,
                                'strict' => true,
                                'referer' => true,
                                'protocols' => ['http', 'https']
                            ]
                        ])
                        ->get($redirectUrl);
                    
                    $body = $response->body();
                }
            }
            
            // Si la respuesta contiene HTML de tracking, la API está bloqueando
            if (str_contains($body, 'bping.php') || (str_contains($body, '<html>') && !str_contains($body, '{'))) {
                Log::warning('API bloqueada por protección anti-bot', ['dni' => $dni]);
                return ['correcto' => false, 'mensaje' => 'API bloqueada por protección anti-bot'];
            }
            
            // Intentar parsear como JSON
            $data = json_decode($body, true);
            
            // Si no es JSON válido, intentar extraer JSON del HTML
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Buscar JSON dentro del HTML
                if (preg_match('/\{[^{}]*"result"[^{}]*\}/', $body, $jsonMatches)) {
                    $data = json_decode($jsonMatches[0], true);
                } else {
                    // Intentar encontrar cualquier JSON en la respuesta
                    preg_match('/\{.*\}/s', $body, $jsonMatches);
                    if (isset($jsonMatches[0])) {
                        $data = json_decode($jsonMatches[0], true);
                    }
                }
            }
            
            if ($response->successful() && $data !== null && isset($data['result'])) {
                // Verificar si la respuesta tiene el formato esperado
                if (is_object($data['result'])) {
                    return [
                        'nombres' => $data['result']->Nombre ?? '',
                        'apellidoPaterno' => $data['result']->Paterno ?? '',
                        'apellidoMaterno' => $data['result']->Materno ?? '',
                        'numeroDocumento' => $data['result']->DNI ?? $dni,
                        'tipoDocumento' => '1',
                    ];
                }
                
                // Intentar con formato array
                if (is_array($data['result'])) {
                    return [
                        'nombres' => $data['result']['Nombre'] ?? '',
                        'apellidoPaterno' => $data['result']['Paterno'] ?? '',
                        'apellidoMaterno' => $data['result']['Materno'] ?? '',
                        'numeroDocumento' => $data['result']['DNI'] ?? $dni,
                        'tipoDocumento' => '1',
                    ];
                }
            }
            
            return ['correcto' => false, 'mensaje' => 'No se encontraron datos'];
        } catch (\Exception $e) {
            Log::error('Error consultando DNI (API principal): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

    private function consultarDniReniecCloud(string $dni): array
    {
        try {
            $url = config('services.consulta_externa.api_reniec_cloud', 'https://api.reniec.cloud/dni/') . $dni;
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'application/json'
                ])
                ->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Formato de api.reniec.cloud puede variar
                if (isset($data['dni']) || isset($data['numeroDocumento'])) {
                    // Normalizar a formato estándar
                    $datosNormalizados = [
                        'nombres' => $data['nombres'] ?? $data['nombre'] ?? '',
                        'apellidoPaterno' => $data['apellidoPaterno'] ?? $data['apellido_paterno'] ?? '',
                        'apellidoMaterno' => $data['apellidoMaterno'] ?? $data['apellido_materno'] ?? '',
                        'numeroDocumento' => $data['dni'] ?? $data['numeroDocumento'] ?? $dni,
                        'tipoDocumento' => '1',
                    ];
                    return [
                
                        'correcto' => true,
                        'datos_completos' => $datosNormalizados,
                    ];
                }
            }
            
            return ['correcto' => false, 'mensaje' => 'API Reniec Cloud no disponible'];
        } catch (\Exception $e) {
            Log::error('Error consultando DNI (Reniec Cloud): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error en API alternativa'];
        }
    }

    private function consultarDniSearchPe(string $dni): array
    {
        try {
            $url = config('services.consulta_externa.api_searchpe', 'https://searchpe.herokuapp.com/public/api/dni/') . $dni;
            
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Formato de searchpe puede variar
                if (isset($data['dni']) || isset($data['numeroDocumento'])) {
                    // Normalizar a formato estándar
                    return [
                        'nombres' => $data['nombres'] ?? $data['nombre'] ?? '',
                        'apellidoPaterno' => $data['apellidoPaterno'] ?? $data['apellido_paterno'] ?? '',
                        'apellidoMaterno' => $data['apellidoMaterno'] ?? $data['apellido_materno'] ?? '',
                        'numeroDocumento' => $data['dni'] ?? $data['numeroDocumento'] ?? $dni,
                        'tipoDocumento' => '1',
                    ];
                }
            }
            
            return ['correcto' => false, 'mensaje' => 'API SearchPe no disponible'];
        } catch (\Exception $e) {
            Log::error('Error consultando DNI (SearchPe): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error en API alternativa'];
        }
    }

    /**
     * Consulta DNI usando apis.net.pe (API funcional)
     * Requiere token de autenticación
     */
    private function consultarDniApisNetPe(string $dni, string $token): array
    {
        try {
            $baseUrl = config('services.consulta_externa.apis_net_pe_url', 'https://api.apis.net.pe/v2/');
            $url = $baseUrl . 'reniec/dni?numero=' . $dni;
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'Referer' => 'https://apis.net.pe/consulta-dni-api',
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ])
                ->withOptions([
                    'verify' => false, // SSL no verificado como en el código original
                ])
                ->get($url);
            
            // Intentar parsear la respuesta como JSON (incluso si el status no es 200)
            $data = $response->json();
            
            // Si hay un mensaje de error
            if (isset($data['message'])) {
                return [
                    'correcto' => false,
                    'mensaje' => $data['message']
                ];
            }
            
            // Formato de apis.net.pe v2 - devolver campos directamente
            if (isset($data['numeroDocumento']) || isset($data['nombres'])) {
                return $data; // Devolver directamente los datos de la API
            }
            
            Log::warning('apis.net.pe no devolvió datos', [
                'dni' => $dni,
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 200)
            ]);
            
            return ['correcto' => false, 'mensaje' => 'apis.net.pe no devolvió datos'];
        } catch (\Exception $e) {
            Log::error('Error consultando DNI (apis.net.pe): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error en apis.net.pe: ' . $e->getMessage()];
        }
    }

    /**
     * Consulta DNI usando JSON.pe (API de pago)
     * Requiere token de autenticación
     */
    private function consultarDniJsonPe(string $dni, string $token): array
    {
        try {
            $baseUrl = config('services.consulta_externa.json_pe_url', 'https://json.pe/api/v1/');
            $url = $baseUrl . 'dni/' . $dni;
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ])
                ->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Formato de JSON.pe
                if (isset($data['dni']) || isset($data['numeroDocumento']) || isset($data['data'])) {
                    $result = $data['data'] ?? $data;
                    // Normalizar a formato estándar
                    return [
                        'nombres' => $result['nombres'] ?? $result['nombre'] ?? '',
                        'apellidoPaterno' => $result['apellidoPaterno'] ?? $result['apellido_paterno'] ?? '',
                        'apellidoMaterno' => $result['apellidoMaterno'] ?? $result['apellido_materno'] ?? '',
                        'numeroDocumento' => $result['dni'] ?? $result['numeroDocumento'] ?? $dni,
                        'tipoDocumento' => '1',
                    ];
                }
            }
            
            return ['correcto' => false, 'mensaje' => 'JSON.pe no devolvió datos'];
        } catch (\Exception $e) {
            Log::error('Error consultando DNI (JSON.pe): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error en JSON.pe'];
        }
    }

    /**
     * Consulta DNI usando APIPERÚ (API de pago)
     * Requiere token de autenticación
     */
    private function consultarDniApiPeru(string $dni, string $token): array
    {
        try {
            $baseUrl = config('services.consulta_externa.apiperu_url', 'https://apiperu.pro/api/');
            $url = $baseUrl . 'dni/' . $dni;
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ])
                ->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Formato de APIPERÚ
                if (isset($data['dni']) || isset($data['numeroDocumento']) || isset($data['data'])) {
                    $result = $data['data'] ?? $data;
                    // Normalizar a formato estándar
                    return [
                        'nombres' => $result['nombres'] ?? $result['nombre'] ?? '',
                        'apellidoPaterno' => $result['apellidoPaterno'] ?? $result['apellido_paterno'] ?? '',
                        'apellidoMaterno' => $result['apellidoMaterno'] ?? $result['apellido_materno'] ?? '',
                        'numeroDocumento' => $result['dni'] ?? $result['numeroDocumento'] ?? $dni,
                        'tipoDocumento' => '1',
                    ];
                }
            }
            
            return ['correcto' => false, 'mensaje' => 'APIPERÚ no devolvió datos'];
        } catch (\Exception $e) {
            Log::error('Error consultando DNI (APIPERÚ): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error en APIPERÚ'];
        }
    }

    public function consultarRuc(string $ruc): array
    {
        // Primero buscar en la base de datos
        $persona = $this->personaJuridicaService->findByRuc($ruc);
        if ($persona) {
            Log::info('RUC encontrado en base de datos', ['ruc' => $ruc]);
            return [
                'correcto' => true,
                'data' => [
                    'ruc' => $persona->getRuc(),
                    'razonSocial' => $persona->getRazonSocial(),
                    'domicilioFiscal' => $persona->getDomicilioFiscal(),
                    'nombreRepresentanteLegal' => $persona->getNombreRepresentanteLegal(),
                    'celular' => $persona->getCelular(),
                ],
                'desde_bd' => true, // Indicador de que viene de BD
            ];
        }

        // Si no está en BD, consultar API
        Log::info('RUC no encontrado en BD, consultando API', ['ruc' => $ruc]);
        
        // Intentar primero con apis.net.pe (API funcional)
        $apisNetPeToken = config('services.consulta_externa.apis_net_pe_token');
        if ($apisNetPeToken) {
            $result = $this->consultarRucApisNetPe($ruc, $apisNetPeToken);
            if ($result['correcto']) {
                $this->guardarPersonaDesdeApi($ruc, '6', $result);
                return $result;
            }
        }
        
        // Intentar con la API principal (puede estar bloqueada)
        $result = $this->consultarRucPrincipal($ruc);
        if ($result['correcto']) {
            $this->guardarPersonaDesdeApi($ruc, '6', $result);
            return $result;
        }
        
        return [
            'correcto' => false, 
            'mensaje' => 'No se pudieron obtener datos. Verifica la configuración de la API en .env'
        ];
    }


    /**
     * Consulta RUC usando apis.net.pe (API funcional)
     * Requiere token de autenticación
     */
    private function consultarRucApisNetPe(string $ruc, string $token): array
    {
        try {
            $baseUrl = config('services.consulta_externa.apis_net_pe_url', 'https://api.apis.net.pe/v2/');
            $url = $baseUrl . 'sunat/ruc?numero=' . $ruc;
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'Referer' => 'http://apis.net.pe/api-ruc',
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ])
                ->withOptions([
                    'verify' => false, // SSL no verificado como en el código original
                ])
                ->get($url);
            
            // Intentar parsear la respuesta como JSON
            $data = $response->json();
            
            // Si hay un mensaje de error
            if (isset($data['message'])) {
                return [
                    'correcto' => false,
                    'mensaje' => $data['message']
                ];
            }
            
            // Formato de apis.net.pe v2 para RUC - devolver todos los campos
            if (isset($data['numeroDocumento']) || isset($data['razonSocial']) || !empty($data)) {
                return [
                    'correcto' => true,
                    'data' => $data,
                    // Campos principales para fácil acceso
                    'ruc' => $data['numeroDocumento'] ?? null,
                    'razon_social' => $data['razonSocial'] ?? null,
                    'estado' => $data['estado'] ?? null,
                    'condicion' => $data['condicion'] ?? null,
                    'direccion' => $data['direccion'] ?? null,
                    'ubigeo' => $data['ubigeo'] ?? null,
                    'distrito' => $data['distrito'] ?? null,
                    'provincia' => $data['provincia'] ?? null,
                    'departamento' => $data['departamento'] ?? null,
                    'es_agente_retencion' => $data['EsAgenteRetencion'] ?? false,
                    'es_buen_contribuyente' => $data['EsBuenContribuyente'] ?? false,
                    'locales_anexos' => $data['localesAnexos'] ?? [],
                ];
            }
            
            Log::warning('apis.net.pe no devolvió datos para RUC', [
                'ruc' => $ruc,
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 200)
            ]);
            
            return ['correcto' => false, 'mensaje' => 'apis.net.pe no devolvió datos'];
        } catch (\Exception $e) {
            Log::error('Error consultando RUC (apis.net.pe): ' . $e->getMessage());
            return ['correcto' => false, 'mensaje' => 'Error en apis.net.pe: ' . $e->getMessage()];
        }
    }

    private function consultarRucPrincipal(string $ruc): array
    {
        try {
            $url = $this->baseUrl . "?documento=RUC&usuario={$this->usuario}&password={$this->password}&nro_documento={$ruc}";
            
            // Primera petición - seguir redirects automáticamente
            $response = Http::timeout(30)
                ->withOptions([
                    'allow_redirects' => [
                        'max' => 5,
                        'strict' => true,
                        'referer' => true,
                        'protocols' => ['http', 'https'],
                        'track_redirects' => true
                    ],
                    'verify' => true
                ])
                ->get($url);
            
            $body = $response->body();
            
            // Si la respuesta es HTML con redirect JavaScript, extraer la URL del redirect
            if (str_contains($body, 'window.location.replace')) {
                preg_match("/window\.location\.replace\('([^']+)'\)/", $body, $matches);
                if (isset($matches[1])) {
                    $redirectUrl = $matches[1];
                    Log::info('API redirige, siguiendo redirect', ['url' => $redirectUrl]);
                    
                    // Hacer la petición al URL del redirect
                    $response = Http::timeout(30)
                        ->withOptions([
                            'verify' => true,
                            'allow_redirects' => [
                                'max' => 5,
                                'strict' => true,
                                'referer' => true,
                                'protocols' => ['http', 'https']
                            ]
                        ])
                        ->get($redirectUrl);
                    
                    $body = $response->body();
                }
            }
            
            // Intentar parsear como JSON
            $data = json_decode($body, true);
            
            // Si no es JSON válido, intentar extraer JSON del HTML
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Buscar JSON dentro del HTML
                if (preg_match('/\{[^{}]*"result"[^{}]*\}/', $body, $jsonMatches)) {
                    $data = json_decode($jsonMatches[0], true);
                } else {
                    // Intentar encontrar cualquier JSON en la respuesta
                    preg_match('/\{.*\}/s', $body, $jsonMatches);
                    if (isset($jsonMatches[0])) {
                        $data = json_decode($jsonMatches[0], true);
                    }
                }
            }
            
            if ($response->successful() && $data !== null) {
                return [
                    'correcto' => true,
                    'data' => $data,
                ];
            }
            
            Log::warning('Error al consultar RUC', [
                'ruc' => $ruc,
                'status' => $response->status(),
                'body' => substr($body, 0, 500)
            ]);
            
            return ['correcto' => false, 'mensaje' => 'No se encontraron datos o la API ha cambiado'];
        } catch (\Exception $e) {
            Log::error('Error consultando RUC: ' . $e->getMessage(), [
                'ruc' => $ruc,
                'exception' => $e
            ]);
            return ['correcto' => false, 'mensaje' => 'Error al consultar RUC: ' . $e->getMessage()];
        }
    }
}

