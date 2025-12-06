# 📋 Ejemplos de API de Ofertas

## ⚠️ IMPORTANTE: Respuestas Enriquecidas

Los endpoints de ofertas ahora incluyen información completa:
- **`ofertas/recibidas`**: Incluye datos completos del producto, stock, unidad, comprador y vendedor
- **`ofertas/mis-ofertas`**: Incluye datos completos del producto, stock, unidad, comprador y vendedor

Cada oferta incluye:
- **`stock`**: Información completa del stock (precio, cantidad, imagen, etc.)
  - **`producto`**: Nombre, descripción, imagen del producto
  - **`unidad`**: Nombre de la unidad de medida (kg, gr, etc.)
- **`comprador`**: Datos del usuario que hizo la oferta (nombre, username, correo, documento)
- **`vendedor`**: Datos del usuario vendedor (nombre, username, correo, documento)

---

## 🔐 Autenticación

Todas las rutas requieren autenticación JWT. Primero obtén un token:

```bash
POST http://localhost:8080/api/auth/login
Content-Type: application/json

{
  "usuario": "tu_usuario",
  "clave": "tu_clave"
}
```

**Respuesta:**
```json
{
  "status": true,
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer"
  }
}
```

Usa el token en todos los requests:
```
Authorization: Bearer TU_TOKEN_JWT
```

---

## 0️⃣ VER OFERTAS RECIBIDAS (con nombres de usuarios)

**Endpoint:** `GET /api/ofertas/recibidas`

**Ejemplo con cURL:**
```bash
curl -X GET http://localhost:8080/api/ofertas/recibidas \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Ejemplo con JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/ofertas/recibidas', {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Respuesta Exitosa (200) - CON INFORMACIÓN COMPLETA:**
```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_oferta": 1,
      "id_stock": 5,
      "id_usuario_ofertante": 3,
      "id_usuario_vendedor": 2,
      "precio_ofertado": 150.50,
      "cantidad": 10,
      "tipo_moneda": "PEN",
      "estado": "PENDIENTE",
      "mensaje": "Estoy interesado en comprar este producto",
      "fecha_respuesta": null,
      "stock": {
        "id_stock": 5,
        "precio": 180.00,
        "imagen": "https://example.com/imagen.jpg",
        "id_usuario": 2,
        "id_producto": 10,
        "cantidad": 50,
        "id_unidad": 1,
        "tipo_moneda": "PEN",
        "recibe_ofertas": true,
        "destacado": false,
        "producto": {
          "id_producto": 10,
          "nombre": "Maíz Amarillo",
          "descripcion": "Maíz amarillo de alta calidad, ideal para consumo",
          "imagen": "https://example.com/maiz.jpg",
          "id_subcategoria": 20,
          "id_usuario": 2,
          "estado": "ACTIVO"
        },
        "unidad": {
          "id_unidad": 1,
          "nombre": "Kilogramo (kg)"
        }
      },
      "comprador": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      },
      "vendedor": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      }
    }
  ],
  "title": "Ofertas recibidas",
  "message": "Ofertas recibidas obtenidas exitosamente"
}
```

**Información incluida:**
- ✅ **Stock completo**: precio, cantidad, imagen, tipo de moneda, etc.
- ✅ **Producto completo**: nombre, descripción, imagen, estado
- ✅ **Unidad**: nombre de la unidad de medida (kg, gr, etc.)
- ✅ **Comprador**: nombre, username, correo, documento
- ✅ **Vendedor**: nombre, username, correo, documento

---

## 1️⃣ VER UNA OFERTA ESPECÍFICA

**Endpoint:** `GET /api/ofertas/{id}`

**Ejemplo con cURL:**
```bash
curl -X GET http://localhost:8080/api/ofertas/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Ejemplo con JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/ofertas/1', {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "id_oferta": 1,
    "id_stock": 5,
    "id_usuario_ofertante": 3,
    "id_usuario_vendedor": 2,
    "precio_ofertado": 150.50,
    "cantidad": 10,
    "tipo_moneda": "PEN",
    "estado": "PENDIENTE",
    "mensaje": "Estoy interesado en comprar este producto",
    "fecha_respuesta": null
  },
  "title": "Detalle de oferta",
  "message": "Oferta obtenida exitosamente"
}
```

**Respuesta de Error (404):**
```json
{
  "status": false,
  "code": 404,
  "message": "Oferta no encontrada",
  "title": "No encontrado"
}
```

---

## 2️⃣ ACEPTAR UNA OFERTA

**Endpoint:** `POST /api/ofertas/{id}/aceptar`

**Nota:** Solo el vendedor (dueño del producto) puede aceptar ofertas.

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/ofertas/1/aceptar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Ejemplo con JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/ofertas/1/aceptar', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "id_oferta": 1,
    "id_stock": 5,
    "id_usuario_ofertante": 3,
    "id_usuario_vendedor": 2,
    "precio_ofertado": 150.50,
    "cantidad": 10,
    "tipo_moneda": "PEN",
    "estado": "ACEPTADA",
    "mensaje": "Estoy interesado en comprar este producto",
    "fecha_respuesta": "2025-12-06 10:30:00"
  },
  "title": "Oferta aceptada",
  "message": "Oferta aceptada exitosamente"
}
```

**Errores Posibles:**

**403 - Sin permisos:**
```json
{
  "status": false,
  "code": 500,
  "message": "No tienes permiso para aceptar esta oferta",
  "title": "Error del servidor"
}
```

**400 - Oferta ya procesada:**
```json
{
  "status": false,
  "code": 500,
  "message": "Esta oferta ya fue procesada",
  "title": "Error del servidor"
}
```

---

## 3️⃣ RECHAZAR UNA OFERTA

**Endpoint:** `POST /api/ofertas/{id}/rechazar`

**Nota:** Solo el vendedor (dueño del producto) puede rechazar ofertas.

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/ofertas/1/rechazar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Ejemplo con JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/ofertas/1/rechazar', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "id_oferta": 1,
    "id_stock": 5,
    "id_usuario_ofertante": 3,
    "id_usuario_vendedor": 2,
    "precio_ofertado": 150.50,
    "cantidad": 10,
    "tipo_moneda": "PEN",
    "estado": "RECHAZADA",
    "mensaje": "Estoy interesado en comprar este producto",
    "fecha_respuesta": "2025-12-06 10:35:00"
  },
  "title": "Oferta rechazada",
  "message": "Oferta rechazada exitosamente"
}
```

**Errores Posibles:**

**403 - Sin permisos:**
```json
{
  "status": false,
  "code": 500,
  "message": "No tienes permiso para rechazar esta oferta",
  "title": "Error del servidor"
}
```

**400 - Oferta ya procesada:**
```json
{
  "status": false,
  "code": 500,
  "message": "Esta oferta ya fue procesada",
  "title": "Error del servidor"
}
```

---

## 4️⃣ CANCELAR UNA OFERTA

**Endpoint:** `POST /api/ofertas/{id}/cancelar`

**Nota:** Solo el usuario que creó la oferta (ofertante) puede cancelarla.

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/ofertas/1/cancelar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Ejemplo con JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/ofertas/1/cancelar', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "id_oferta": 1,
    "id_stock": 5,
    "id_usuario_ofertante": 3,
    "id_usuario_vendedor": 2,
    "precio_ofertado": 150.50,
    "cantidad": 10,
    "tipo_moneda": "PEN",
    "estado": "CANCELADA",
    "mensaje": "Estoy interesado en comprar este producto",
    "fecha_respuesta": "2025-12-06 10:40:00"
  },
  "title": "Oferta cancelada",
  "message": "Oferta cancelada exitosamente"
}
```

**Errores Posibles:**

**403 - Sin permisos:**
```json
{
  "status": false,
  "code": 500,
  "message": "No tienes permiso para cancelar esta oferta",
  "title": "Error del servidor"
}
```

**400 - Oferta ya procesada:**
```json
{
  "status": false,
  "code": 500,
  "message": "Esta oferta ya fue procesada",
  "title": "Error del servidor"
}
```

---

## 📊 RESUMEN DE ENDPOINTS

| Método | Endpoint | Descripción | Quién puede usarlo |
|--------|----------|-------------|-------------------|
| `GET` | `/api/ofertas/{id}` | Ver una oferta específica | Cualquiera autenticado |
| `POST` | `/api/ofertas/{id}/aceptar` | Aceptar una oferta | Solo el vendedor |
| `POST` | `/api/ofertas/{id}/rechazar` | Rechazar una oferta | Solo el vendedor |
| `POST` | `/api/ofertas/{id}/cancelar` | Cancelar una oferta | Solo el ofertante |

---

## 🔄 ESTADOS DE UNA OFERTA

- **PENDIENTE**: La oferta está esperando respuesta del vendedor
- **ACEPTADA**: El vendedor aceptó la oferta
- **RECHAZADA**: El vendedor rechazó la oferta
- **CANCELADA**: El ofertante canceló su oferta

---

## 💡 EJEMPLO DE FLUJO COMPLETO

1. **Ver ofertas recibidas:**
   ```bash
   GET /api/ofertas/recibidas
   ```

2. **Ver detalles de una oferta:**
   ```bash
   GET /api/ofertas/1
   ```

3. **Aceptar la oferta:**
   ```bash
   POST /api/ofertas/1/aceptar
   ```

   O **Rechazar la oferta:**
   ```bash
   POST /api/ofertas/1/rechazar
   ```

---

## 🧪 PRUEBAS RÁPIDAS CON cURL

```bash
# 1. Obtener token
TOKEN=$(curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"usuario":"tu_usuario","clave":"tu_clave"}' \
  | jq -r '.data.token')

# 2. Ver ofertas recibidas
curl -X GET http://localhost:8080/api/ofertas/recibidas \
  -H "Authorization: Bearer $TOKEN"

# 3. Ver una oferta específica
curl -X GET http://localhost:8080/api/ofertas/1 \
  -H "Authorization: Bearer $TOKEN"

# 4. Aceptar oferta
curl -X POST http://localhost:8080/api/ofertas/1/aceptar \
  -H "Authorization: Bearer $TOKEN"

# 5. Rechazar oferta
curl -X POST http://localhost:8080/api/ofertas/1/rechazar \
  -H "Authorization: Bearer $TOKEN"
```

---

## 📝 NOTAS IMPORTANTES

1. **Todas las rutas requieren autenticación JWT**
2. **Solo el vendedor puede aceptar/rechazar ofertas**
3. **Solo el ofertante puede cancelar ofertas**
4. **No se pueden modificar ofertas ya procesadas** (ACEPTADA, RECHAZADA, CANCELADA)
5. **El campo `fecha_respuesta` se actualiza automáticamente** cuando se acepta, rechaza o cancela

