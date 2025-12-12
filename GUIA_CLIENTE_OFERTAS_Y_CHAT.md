# 🛒 Guía Completa para Cliente: Ofertas y Chat

## 📋 Índice
1. [Requisitos Previos](#requisitos-previos)
2. [Mis Ofertas](#mis-ofertas)
3. [Crear Oferta](#crear-oferta)
4. [Gestionar Ofertas](#gestionar-ofertas)
5. [Chat y Mensajería](#chat-y-mensajería)
6. [Flujo Completo](#flujo-completo)

---

## 🔐 Requisitos Previos

**IMPORTANTE**: Todas las rutas requieren autenticación JWT. Primero debes obtener un token:

### 1. Obtener Token JWT

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

**Guarda el token** para usarlo en todos los siguientes requests con el header:
```
Authorization: Bearer TU_TOKEN_AQUI
```

---

## 🛒 Mis Ofertas

### Ver todas mis ofertas enviadas

**Endpoint:** `GET /api/ofertas/mis-ofertas`

Esta API muestra todas las ofertas que has enviado como cliente. El usuario se obtiene automáticamente del token JWT.

**Ejemplo con cURL:**
```bash
curl -X GET http://localhost:8080/api/ofertas/mis-ofertas \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Ejemplo con JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/ofertas/mis-ofertas', {
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
  "success": true,
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
    },
    {
      "id_oferta": 2,
      "id_stock": 8,
      "id_usuario_ofertante": 3,
      "id_usuario_vendedor": 4,
      "precio_ofertado": 200.00,
      "cantidad": 5,
      "tipo_moneda": "PEN",
      "estado": "ACEPTADA",
      "mensaje": "¿Podríamos negociar el precio?",
      "fecha_respuesta": "2025-12-06 10:30:00",
      "stock": {
        "id_stock": 8,
        "precio": 250.00,
        "imagen": "https://example.com/trigo.jpg",
        "producto": {
          "id_producto": 15,
          "nombre": "Trigo",
          "descripcion": "Trigo de primera calidad",
          "imagen": "https://example.com/trigo.jpg"
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
        "id_usuario": 4,
        "nombre": "Carlos López",
        "username": "carloslopez",
        "correo": "carlos@example.com",
        "documento": "11223344"
      }
    }
  ],
  "title": "Mis ofertas",
  "message": "Ofertas obtenidas exitosamente"
}
```

**Información incluida:**
- ✅ **Datos de la oferta**: precio ofertado, cantidad, estado, mensaje
- ✅ **Stock completo**: precio original, cantidad disponible, imagen
- ✅ **Producto completo**: nombre, descripción, imagen
- ✅ **Unidad**: nombre de la unidad de medida (kg, gr, etc.)
- ✅ **Vendedor**: nombre, username, correo, documento
- ✅ **Comprador (tú)**: nombre, username, correo, documento

**Estados posibles:**
- `PENDIENTE`: La oferta está esperando respuesta del vendedor
- `ACEPTADA`: El vendedor aceptó tu oferta ✅
- `RECHAZADA`: El vendedor rechazó tu oferta ❌
- `CANCELADA`: Cancelaste tu oferta

---

## ➕ Crear Oferta

### Enviar una oferta a un producto

**Endpoint:** `POST /api/ofertas`

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "id_stock": 5,
  "precio_ofertado": 150.50,
  "cantidad": 10,
  "tipo_moneda": "PEN",
  "mensaje": "Estoy interesado en comprar este producto. ¿Podríamos negociar el precio?"
}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/ofertas \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "id_stock": 5,
    "precio_ofertado": 150.50,
    "cantidad": 10,
    "tipo_moneda": "PEN",
    "mensaje": "Estoy interesado en comprar este producto"
  }'
```

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "code": 201,
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
  "title": "Oferta creada",
  "message": "Oferta creada exitosamente"
}
```

**Errores Posibles:**
- `400`: El producto no acepta ofertas (`recibe_ofertas = false`)
- `400`: Ya tienes una oferta pendiente para este producto
- `400`: No puedes hacer ofertas a tus propios productos
- `404`: Stock no encontrado

---

## 🔄 Gestionar Ofertas

### Ver una oferta específica

**Endpoint:** `GET /api/ofertas/{id}`

**Ejemplo:**
```bash
curl -X GET http://localhost:8080/api/ofertas/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
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

---

### Cancelar una oferta

**Endpoint:** `POST /api/ofertas/{id}/cancelar`

**Nota:** Solo tú (el ofertante) puedes cancelar tus propias ofertas. Solo puedes cancelar ofertas en estado `PENDIENTE`.

**Ejemplo:**
```bash
curl -X POST http://localhost:8080/api/ofertas/1/cancelar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
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
- `403`: No tienes permiso para cancelar esta oferta (solo puedes cancelar tus propias ofertas)
- `400`: Esta oferta ya fue procesada (no se puede cancelar una oferta ACEPTADA o RECHAZADA)
- `404`: Oferta no encontrada

---

## 💬 Chat y Mensajería

### Enviar un mensaje

**Endpoint:** `POST /api/mensajes`

Puedes enviar mensajes generales o mensajes asociados a una oferta.

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
Content-Type: application/json
```

**Body (JSON) - Mensaje general:**
```json
{
  "id_usuario_destinatario": 2,
  "mensaje": "Hola, me interesa tu producto. ¿Podríamos hablar sobre el precio?"
}
```

**Body (JSON) - Mensaje asociado a una oferta:**
```json
{
  "id_oferta": 1,
  "id_usuario_destinatario": 2,
  "mensaje": "¿Podrías considerar mi oferta? Estoy dispuesto a negociar."
}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/mensajes \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "id_usuario_destinatario": 2,
    "mensaje": "Hola, me interesa tu producto"
  }'
```

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "code": 201,
  "data": {
    "id_mensaje": 1,
    "id_oferta": null,
    "id_usuario_remitente": 3,
    "id_usuario_destinatario": 2,
    "mensaje": "Hola, me interesa tu producto",
    "leido": false,
    "fecha_leido": null,
    "fecha_creacion": "2025-12-06 10:00:00"
  },
  "title": "Mensaje enviado",
  "message": "Mensaje enviado exitosamente"
}
```

---

### Ver mensajes de una oferta

**Endpoint:** `GET /api/mensajes/oferta/{idOferta}`

Muestra todos los mensajes relacionados con una oferta específica. Incluye nombres de remitente y destinatario.

**Ejemplo:**
```bash
curl -X GET http://localhost:8080/api/mensajes/oferta/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200) - CON NOMBRES DE USUARIOS:**
```json
{
  "success": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 1,
      "id_oferta": 1,
      "id_usuario_remitente": 3,
      "id_usuario_destinatario": 2,
      "mensaje": "Hola, me interesa tu producto",
      "leido": true,
      "fecha_leido": "2025-12-06 10:45:00",
      "fecha_creacion": "2025-12-06 10:00:00",
      "remitente": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      },
      "destinatario": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      }
    },
    {
      "id_mensaje": 2,
      "id_oferta": 1,
      "id_usuario_remitente": 2,
      "id_usuario_destinatario": 3,
      "mensaje": "Claro, podemos negociar. ¿Qué precio tienes en mente?",
      "leido": false,
      "fecha_leido": null,
      "fecha_creacion": "2025-12-06 10:30:00",
      "remitente": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      },
      "destinatario": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      }
    }
  ],
  "title": "Mensajes de la oferta",
  "message": "Mensajes obtenidos exitosamente"
}
```

---

### Ver conversación con un usuario

**Endpoint:** `GET /api/mensajes/conversacion/{idUsuario}`

Muestra todos los mensajes entre tú y otro usuario específico. El usuario se obtiene del token JWT.

**Ejemplo:**
```bash
curl -X GET http://localhost:8080/api/mensajes/conversacion/2 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200) - CON NOMBRES Y FECHAS:**
```json
{
  "success": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 1,
      "id_oferta": null,
      "id_usuario_remitente": 3,
      "id_usuario_destinatario": 2,
      "mensaje": "Hola, me interesa tu producto",
      "leido": true,
      "fecha_leido": "2025-12-06 10:45:00",
      "fecha_creacion": "2025-12-06 10:00:00",
      "remitente": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      },
      "destinatario": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      }
    },
    {
      "id_mensaje": 2,
      "id_oferta": null,
      "id_usuario_remitente": 2,
      "id_usuario_destinatario": 3,
      "mensaje": "Perfecto, ¿qué te interesa?",
      "leido": true,
      "fecha_leido": "2025-12-06 10:46:00",
      "fecha_creacion": "2025-12-06 10:15:00",
      "remitente": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      },
      "destinatario": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      }
    }
  ],
  "title": "Conversación",
  "message": "Conversación obtenida exitosamente"
}
```

---

### Ver mensajes enviados

**Endpoint:** `GET /api/mensajes/enviados`

Muestra todos los mensajes que has enviado. El usuario se obtiene del token JWT.

**Ejemplo:**
```bash
curl -X GET http://localhost:8080/api/mensajes/enviados \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200) - CON NOMBRES:**
```json
{
  "success": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 1,
      "id_oferta": 1,
      "id_usuario_remitente": 3,
      "id_usuario_destinatario": 2,
      "mensaje": "Hola, me interesa tu producto",
      "leido": true,
      "fecha_leido": "2025-12-06 10:45:00",
      "fecha_creacion": "2025-12-06 10:00:00",
      "remitente": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      },
      "destinatario": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      }
    }
  ],
  "title": "Mensajes enviados",
  "message": "Mensajes enviados obtenidos exitosamente"
}
```

---

### Ver mensajes recibidos

**Endpoint:** `GET /api/mensajes/recibidos`

Muestra todos los mensajes que has recibido. El usuario se obtiene del token JWT.

**Ejemplo:**
```bash
curl -X GET http://localhost:8080/api/mensajes/recibidos \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200) - CON NOMBRES:**
```json
{
  "success": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 2,
      "id_oferta": 1,
      "id_usuario_remitente": 2,
      "id_usuario_destinatario": 3,
      "mensaje": "Claro, podemos negociar",
      "leido": false,
      "fecha_leido": null,
      "fecha_creacion": "2025-12-06 10:30:00",
      "remitente": {
        "id_usuario": 2,
        "nombre": "María García",
        "username": "mariagarcia",
        "correo": "maria@example.com",
        "documento": "87654321"
      },
      "destinatario": {
        "id_usuario": 3,
        "nombre": "Juan Pérez",
        "username": "juanperez",
        "correo": "juan@example.com",
        "documento": "12345678"
      }
    }
  ],
  "title": "Mensajes recibidos",
  "message": "Mensajes recibidos obtenidos exitosamente"
}
```

---

### Marcar mensaje como leído

**Endpoint:** `POST /api/mensajes/{id}/leido`

Marca un mensaje recibido como leído. Solo puedes marcar como leído los mensajes que recibiste.

**Ejemplo:**
```bash
curl -X POST http://localhost:8080/api/mensajes/2/leido \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "code": 200,
  "data": {
    "id_mensaje": 2,
    "id_oferta": 1,
    "id_usuario_remitente": 2,
    "id_usuario_destinatario": 3,
    "mensaje": "Claro, podemos negociar",
    "leido": true,
    "fecha_leido": "2025-12-06 11:00:00"
  },
  "title": "Mensaje leído",
  "message": "Mensaje marcado como leído exitosamente"
}
```

---

### Contar mensajes no leídos

**Endpoint:** `GET /api/mensajes/no-leidos/cantidad`

Obtiene la cantidad de mensajes que has recibido y aún no has leído.

**Ejemplo:**
```bash
curl -X GET http://localhost:8080/api/mensajes/no-leidos/cantidad \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "code": 200,
  "data": {
    "cantidad": 3
  },
  "title": "Mensajes no leídos",
  "message": "Cantidad de mensajes no leídos obtenida exitosamente"
}
```

---

## 🔄 Flujo Completo

### Escenario: Quieres comprar un producto y negociar el precio

1. **Ver un producto que acepta ofertas**
   - El producto debe tener `recibe_ofertas = true`

2. **Crear una oferta**
   ```bash
   POST /api/ofertas
   {
     "id_stock": 5,
     "precio_ofertado": 150.50,
     "cantidad": 10,
     "tipo_moneda": "PEN",
     "mensaje": "Estoy interesado en comprar este producto"
   }
   ```

3. **Ver tus ofertas**
   ```bash
   GET /api/ofertas/mis-ofertas
   ```
   - Verás el estado de tu oferta: `PENDIENTE`, `ACEPTADA`, `RECHAZADA`, o `CANCELADA`

4. **Chatear con el vendedor sobre la oferta**
   ```bash
   POST /api/mensajes
   {
     "id_oferta": 1,
     "id_usuario_destinatario": 2,
     "mensaje": "¿Podrías considerar mi oferta? Estoy dispuesto a negociar."
   }
   ```

5. **Ver los mensajes de la oferta**
   ```bash
   GET /api/mensajes/oferta/1
   ```
   - Verás todos los mensajes entre tú y el vendedor sobre esta oferta

6. **Verificar el estado de tu oferta**
   ```bash
   GET /api/ofertas/1
   ```
   - Si el vendedor aceptó: `estado: "ACEPTADA"` ✅
   - Si el vendedor rechazó: `estado: "RECHAZADA"` ❌
   - Si aún está pendiente: `estado: "PENDIENTE"` ⏳

7. **Si quieres cancelar tu oferta**
   ```bash
   POST /api/ofertas/1/cancelar
   ```
   - Solo puedes cancelar ofertas en estado `PENDIENTE`

---

## 📊 Resumen de Endpoints para Cliente

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/ofertas/mis-ofertas` | Ver todas mis ofertas enviadas |
| `POST` | `/api/ofertas` | Crear una nueva oferta |
| `GET` | `/api/ofertas/{id}` | Ver una oferta específica |
| `POST` | `/api/ofertas/{id}/cancelar` | Cancelar mi oferta |
| `POST` | `/api/mensajes` | Enviar un mensaje |
| `GET` | `/api/mensajes/oferta/{idOferta}` | Ver mensajes de una oferta |
| `GET` | `/api/mensajes/conversacion/{idUsuario}` | Ver conversación con un usuario |
| `GET` | `/api/mensajes/enviados` | Ver mensajes enviados |
| `GET` | `/api/mensajes/recibidos` | Ver mensajes recibidos |
| `POST` | `/api/mensajes/{id}/leido` | Marcar mensaje como leído |
| `GET` | `/api/mensajes/no-leidos/cantidad` | Contar mensajes no leídos |

---

## 📝 Notas Importantes

### Ofertas:
- ✅ Solo productos con `recibe_ofertas = true` aceptan ofertas
- ✅ No puedes hacer ofertas a tus propios productos
- ✅ Solo puedes tener una oferta pendiente por producto
- ✅ Solo tú puedes cancelar tus propias ofertas
- ✅ Solo puedes cancelar ofertas en estado `PENDIENTE`

### Mensajes:
- ✅ Los mensajes pueden ser generales o asociados a una oferta
- ✅ Si un mensaje está asociado a una oferta, solo los usuarios involucrados pueden enviar mensajes
- ✅ Todos los mensajes incluyen nombres de remitente y destinatario
- ✅ Los mensajes incluyen `fecha_creacion` y `fecha_leido` (si fue leído)
- ✅ Puedes ver si un mensaje fue leído con el campo `leido`

### Estados de Ofertas:
- `PENDIENTE`: La oferta está esperando respuesta del vendedor ⏳
- `ACEPTADA`: El vendedor aceptó tu oferta ✅
- `RECHAZADA`: El vendedor rechazó tu oferta ❌
- `CANCELADA`: Cancelaste tu oferta 🚫

---

## 🚀 Ejemplo de Uso Completo

```bash
# 1. Obtener token
TOKEN=$(curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"usuario":"tu_usuario","clave":"tu_clave"}' \
  | jq -r '.data.token')

# 2. Ver mis ofertas
curl -X GET http://localhost:8080/api/ofertas/mis-ofertas \
  -H "Authorization: Bearer $TOKEN"

# 3. Crear una oferta
curl -X POST http://localhost:8080/api/ofertas \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "id_stock": 5,
    "precio_ofertado": 150.50,
    "cantidad": 10,
    "tipo_moneda": "PEN",
    "mensaje": "Estoy interesado"
  }'

# 4. Enviar mensaje sobre la oferta
curl -X POST http://localhost:8080/api/mensajes \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "id_oferta": 1,
    "id_usuario_destinatario": 2,
    "mensaje": "¿Podríamos negociar?"
  }'

# 5. Ver mensajes de la oferta
curl -X GET http://localhost:8080/api/mensajes/oferta/1 \
  -H "Authorization: Bearer $TOKEN"

# 6. Ver conversación con el vendedor
curl -X GET http://localhost:8080/api/mensajes/conversacion/2 \
  -H "Authorization: Bearer $TOKEN"

# 7. Contar mensajes no leídos
curl -X GET http://localhost:8080/api/mensajes/no-leidos/cantidad \
  -H "Authorization: Bearer $TOKEN"
```

---

## 📞 Soporte

Si tienes problemas o preguntas sobre estas APIs, revisa:
- Los logs del servidor
- La documentación de Laravel
- El código fuente en `app/Presentation/Http/Controllers/OfertaController.php` y `MensajeController.php`

