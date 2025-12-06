# Información sobre la API de Consulta Externa (DNI/RUC)

## API Actual

### Configuración
- **URL Base**: `https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php`
- **Usuario**: `10447915125`
- **Password**: `985511933`

### Variables de Entorno

Puedes configurar estas credenciales en tu archivo `.env`:

```env
CONSULTA_EXTERNA_USUARIO=10447915125
CONSULTA_EXTERNA_PASSWORD=985511933
CONSULTA_EXTERNA_BASE_URL=https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php
```

### Uso

**Consulta DNI:**
```bash
POST /api/consultas/dni
Authorization: Bearer {token}
Content-Type: application/json

{
  "dni": "12345678"
}
```

**Consulta RUC:**
```bash
POST /api/consultas/ruc
Authorization: Bearer {token}
Content-Type: application/json

{
  "ruc": "12345678901"
}
```

## APIs Alternativas (Comentadas en código original)

### Para DNI:

1. **api.reniec.cloud** (Comentada)
   ```
   https://api.reniec.cloud/dni/{dni}
   ```
   - No requiere autenticación
   - Formato: GET directo

2. **searchpe.herokuapp.com** (Comentada)
   ```
   https://searchpe.herokuapp.com/public/api/dni/{dni}
   ```
   - No requiere autenticación
   - Formato: GET directo

### Para RUC:

1. **api.sunat.cloud** (Comentada)
   ```
   https://api.sunat.cloud/ruc/{ruc}
   ```
   - No requiere autenticación
   - Formato: GET directo

## Verificación de la API

Para verificar si la API actual está funcionando, puedes:

1. **Probar directamente en el navegador:**
   ```
   https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10447915125&password=985511933&nro_documento=12345678
   ```

2. **Usar curl:**
   ```bash
   curl "https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10447915125&password=985511933&nro_documento=12345678"
   ```

3. **Probar desde la API Laravel:**
   ```bash
   curl -X POST http://localhost/api/consultas/dni \
     -H "Authorization: Bearer {token}" \
     -H "Content-Type: application/json" \
     -d '{"dni": "12345678"}'
   ```

## ⚠️ Problema Actual con las APIs Gratuitas

**Todas las APIs gratuitas están bloqueadas o no disponibles:**

- ❌ **facturacionelectronica.us**: Bloqueada por protección anti-bot
- ❌ **api.reniec.cloud**: Redirige (no disponible)
- ❌ **searchpe.herokuapp.com**: App eliminada de Heroku

## ✅ Solución: APIs de Pago

Para obtener datos de DNI/RUC de forma confiable, se recomienda usar **APIs de pago** que están disponibles y funcionan correctamente:

### 1. JSON.pe (Recomendado)

**URL**: https://json.pe/

**Características**:
- API estable y rápida
- Consulta de DNI, RUC, placas, SOAT
- Precios accesibles
- Documentación completa

**Configuración en `.env`**:
```env
JSON_PE_TOKEN=tu_token_aqui
JSON_PE_URL=https://json.pe/api/v1/
```

### 2. APIPERÚ

**URL**: https://apiperu.pro/

**Características**:
- Cumple con normativas vigentes
- Consulta de RUC y DNI
- Servicio empresarial

**Configuración en `.env`**:
```env
APIPERU_TOKEN=tu_token_aqui
APIPERU_URL=https://apiperu.pro/api/
```

### 3. APIsPERU

**URL**: https://apisperu.pe/

**Características**:
- Soluciones integrales
- Facturación electrónica
- Consulta de RUC y DNI

### Cómo Configurar

1. **Regístrate** en una de las APIs de pago
2. **Obtén tu token** de autenticación
3. **Agrega las variables** en tu archivo `.env`:
   ```env
   JSON_PE_TOKEN=tu_token_aqui
   ```
4. **El servicio automáticamente** usará la API de pago si está configurada

## Notas Importantes

1. **Seguridad**: Las credenciales ahora están en variables de entorno, no hardcodeadas
2. **Logging**: Los errores se registran en los logs de Laravel
3. **Timeout**: La petición tiene un timeout de 30 segundos
4. **Formato de Respuesta**: El servicio maneja tanto objetos como arrays en la respuesta
5. **Fallback**: El servicio intenta múltiples APIs si la principal falla
6. **Headers de Navegador**: Se envían headers para simular un navegador real

## Si la API no funciona

Si la API actual deja de funcionar, puedes:

1. Cambiar las variables de entorno para usar una API alternativa
2. Modificar el servicio `ConsultaExternaService` para agregar fallback a APIs alternativas
3. Contactar al proveedor de la API para verificar el estado del servicio

## Mejoras Futuras

- [ ] Implementar fallback automático a APIs alternativas
- [ ] Agregar caché de consultas para reducir llamadas a la API
- [ ] Implementar rate limiting para evitar exceder límites de la API
- [ ] Agregar métricas y monitoreo de la API

