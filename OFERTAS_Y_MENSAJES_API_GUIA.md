# 💬 Guía Completa de API de Ofertas y Mensajería

## 📋 Índice
1. [Requisitos Previos](#requisitos-previos)
2. [Endpoints de Ofertas](#endpoints-de-ofertas)
3. [Endpoints de Mensajes](#endpoints-de-mensajes)
4. [Ejemplos Detallados](#ejemplos-detallados)
5. [Flujo Completo de Uso](#flujo-completo-de-uso)

---

## 🔐 Requisitos Previos

**IMPORTANTE**: Todas las rutas requieren autenticación JWT. Primero debes obtener un token:

### 1. Obtener Token JWT

```bash
POST http://192.168.101.9:8080/api/auth/login
Content-Type: application/json

{
  "usuario": "nilsen",
  "clave": "123456"
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

## 📡 Endpoints de Ofertas

### 1. **POST** `/api/ofertas` - Crear una oferta
### 2. **GET** `/api/ofertas/{id}` - Ver una oferta específica
### 3. **GET** `/api/ofertas/stock/{idStock}` - Ver ofertas de un producto
### 4. **GET** `/api/ofertas/mis-ofertas` - Ver mis ofertas enviadas
### 5. **GET** `/api/ofertas/recibidas` - Ver ofertas recibidas
### 6. **POST** `/api/ofertas/{id}/aceptar` - Aceptar una oferta
### 7. **POST** `/api/ofertas/{id}/rechazar` - Rechazar una oferta
### 8. **POST** `/api/ofertas/{id}/cancelar` - Cancelar una oferta (solo el ofertante)

---

## 💬 Endpoints de Mensajes

### 1. **POST** `/api/mensajes` - Enviar un mensaje
### 2. **GET** `/api/mensajes/{id}` - Ver un mensaje específico
### 3. **GET** `/api/mensajes/oferta/{idOferta}` - Ver mensajes de una oferta
### 4. **GET** `/api/mensajes/conversacion/{idUsuario}` - Ver conversación con un usuario
### 5. **GET** `/api/mensajes/enviados` - Ver mensajes enviados
### 6. **GET** `/api/mensajes/recibidos` - Ver mensajes recibidos
### 7. **POST** `/api/mensajes/{id}/leido` - Marcar mensaje como leído
### 8. **GET** `/api/mensajes/no-leidos/cantidad` - Contar mensajes no leídos

---

## 📝 Ejemplos Detallados

### 🛒 OFERTAS

#### 1️⃣ CREAR UNA OFERTA

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
curl -X POST http://192.168.101.9:8080/api/ofertas \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
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
  "status": true,
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

#### 2️⃣ VER OFERTAS DE UN PRODUCTO

**Endpoint:** `GET /api/ofertas/stock/{idStock}`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/ofertas/stock/5 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
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
      "fecha_respuesta": null
    },
    {
      "id_oferta": 2,
      "id_stock": 5,
      "id_usuario_ofertante": 4,
      "id_usuario_vendedor": 2,
      "precio_ofertado": 145.00,
      "cantidad": 15,
      "tipo_moneda": "PEN",
      "estado": "PENDIENTE",
      "mensaje": null,
      "fecha_respuesta": null
    }
  ],
  "title": "Ofertas del producto",
  "message": "Ofertas obtenidas exitosamente"
}
```

---

#### 3️⃣ VER MIS OFERTAS ENVIADAS

**Endpoint:** `GET /api/ofertas/mis-ofertas`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/ofertas/mis-ofertas \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
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
      "fecha_respuesta": null
    }
  ],
  "title": "Mis ofertas",
  "message": "Ofertas obtenidas exitosamente"
}
```

---

#### 4️⃣ VER OFERTAS RECIBIDAS

**Endpoint:** `GET /api/ofertas/recibidas`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/ofertas/recibidas \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
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
      "fecha_respuesta": null
    }
  ],
  "title": "Ofertas recibidas",
  "message": "Ofertas recibidas obtenidas exitosamente"
}
```

---

#### 5️⃣ ACEPTAR UNA OFERTA

**Endpoint:** `POST /api/ofertas/{id}/aceptar`

**Ejemplo:**
```bash
curl -X POST http://192.168.101.9:8080/api/ofertas/1/aceptar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
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
- `403`: No tienes permiso para aceptar esta oferta (solo el vendedor puede aceptar)
- `400`: Esta oferta ya fue procesada

---

#### 6️⃣ RECHAZAR UNA OFERTA

**Endpoint:** `POST /api/ofertas/{id}/rechazar`

**Ejemplo:**
```bash
curl -X POST http://192.168.101.9:8080/api/ofertas/1/rechazar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
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

---

#### 7️⃣ CANCELAR UNA OFERTA

**Endpoint:** `POST /api/ofertas/{id}/cancelar`

**Nota:** Solo el usuario que creó la oferta puede cancelarla.

**Ejemplo:**
```bash
curl -X POST http://192.168.101.9:8080/api/ofertas/1/cancelar \
  -H "Authorization: Bearer TU_TOKEN_JWT"
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

---

### 💬 MENSAJES

#### 1️⃣ ENVIAR UN MENSAJE

**Endpoint:** `POST /api/mensajes`

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
curl -X POST http://192.168.101.9:8080/api/mensajes \
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
  "status": true,
  "code": 201,
  "data": {
    "id_mensaje": 1,
    "id_oferta": null,
    "id_usuario_remitente": 3,
    "id_usuario_destinatario": 2,
    "mensaje": "Hola, me interesa tu producto",
    "leido": false,
    "fecha_leido": null
  },
  "title": "Mensaje enviado",
  "message": "Mensaje enviado exitosamente"
}
```

---

#### 2️⃣ VER MENSAJES DE UNA OFERTA

**Endpoint:** `GET /api/mensajes/oferta/{idOferta}`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/mensajes/oferta/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 1,
      "id_oferta": 1,
      "id_usuario_remitente": 3,
      "id_usuario_destinatario": 2,
      "mensaje": "Hola, me interesa tu producto",
      "leido": true,
      "fecha_leido": "2025-12-06 10:45:00"
    },
    {
      "id_mensaje": 2,
      "id_oferta": 1,
      "id_usuario_remitente": 2,
      "id_usuario_destinatario": 3,
      "mensaje": "Claro, podemos negociar. ¿Qué precio tienes en mente?",
      "leido": false,
      "fecha_leido": null
    }
  ],
  "title": "Mensajes de la oferta",
  "message": "Mensajes obtenidos exitosamente"
}
```

---

#### 3️⃣ VER CONVERSACIÓN CON UN USUARIO

**Endpoint:** `GET /api/mensajes/conversacion/{idUsuario}`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/mensajes/conversacion/2 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 1,
      "id_oferta": null,
      "id_usuario_remitente": 3,
      "id_usuario_destinatario": 2,
      "mensaje": "Hola, me interesa tu producto",
      "leido": true,
      "fecha_leido": "2025-12-06 10:45:00"
    },
    {
      "id_mensaje": 2,
      "id_oferta": null,
      "id_usuario_remitente": 2,
      "id_usuario_destinatario": 3,
      "mensaje": "Perfecto, ¿qué te interesa?",
      "leido": true,
      "fecha_leido": "2025-12-06 10:46:00"
    }
  ],
  "title": "Conversación",
  "message": "Conversación obtenida exitosamente"
}
```

---

#### 4️⃣ VER MENSAJES ENVIADOS

**Endpoint:** `GET /api/mensajes/enviados`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/mensajes/enviados \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 1,
      "id_oferta": 1,
      "id_usuario_remitente": 3,
      "id_usuario_destinatario": 2,
      "mensaje": "Hola, me interesa tu producto",
      "leido": true,
      "fecha_leido": "2025-12-06 10:45:00"
    }
  ],
  "title": "Mensajes enviados",
  "message": "Mensajes enviados obtenidos exitosamente"
}
```

---

#### 5️⃣ VER MENSAJES RECIBIDOS

**Endpoint:** `GET /api/mensajes/recibidos`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/mensajes/recibidos \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_mensaje": 2,
      "id_oferta": 1,
      "id_usuario_remitente": 2,
      "id_usuario_destinatario": 3,
      "mensaje": "Claro, podemos negociar",
      "leido": false,
      "fecha_leido": null
    }
  ],
  "title": "Mensajes recibidos",
  "message": "Mensajes recibidos obtenidos exitosamente"
}
```

---

#### 6️⃣ MARCAR MENSAJE COMO LEÍDO

**Endpoint:** `POST /api/mensajes/{id}/leido`

**Ejemplo:**
```bash
curl -X POST http://192.168.101.9:8080/api/mensajes/2/leido \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
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

#### 7️⃣ CONTAR MENSAJES NO LEÍDOS

**Endpoint:** `GET /api/mensajes/no-leidos/cantidad`

**Ejemplo:**
```bash
curl -X GET http://192.168.101.9:8080/api/mensajes/no-leidos/cantidad \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "cantidad": 3
  },
  "title": "Mensajes no leídos",
  "message": "Cantidad de mensajes no leídos obtenida exitosamente"
}
```

---

## 🔄 Flujo Completo de Uso

### Escenario: Usuario A quiere hacer una oferta al producto de Usuario B

1. **Usuario A ve un producto con `recibe_ofertas = true`**
   - Obtiene información del stock/producto

2. **Usuario A crea una oferta**
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

3. **Usuario B recibe la oferta**
   ```bash
   GET /api/ofertas/recibidas
   ```

4. **Usuario B envía un mensaje para negociar**
   ```bash
   POST /api/mensajes
   {
     "id_oferta": 1,
     "id_usuario_destinatario": 3,
     "mensaje": "¿Podrías aumentar un poco el precio? Estoy pensando en 160"
   }
   ```

5. **Usuario A responde**
   ```bash
   POST /api/mensajes
   {
     "id_oferta": 1,
     "id_usuario_destinatario": 2,
     "mensaje": "Podría llegar hasta 155, ¿te parece bien?"
   }
   ```

6. **Usuario B acepta la oferta**
   ```bash
   POST /api/ofertas/1/aceptar
   ```

7. **Usuario A verifica el estado de su oferta**
   ```bash
   GET /api/ofertas/1
   ```

---

## 📌 Notas Importantes

### Ofertas:
- ✅ Solo productos con `recibe_ofertas = true` aceptan ofertas
- ✅ Un usuario no puede hacer ofertas a sus propios productos
- ✅ Solo se puede tener una oferta pendiente por producto
- ✅ Solo el vendedor puede aceptar/rechazar ofertas
- ✅ Solo el ofertante puede cancelar sus ofertas

### Mensajes:
- ✅ Los mensajes pueden ser generales o asociados a una oferta
- ✅ Si un mensaje está asociado a una oferta, solo los usuarios involucrados pueden enviar mensajes
- ✅ El destinatario debe ser el otro usuario de la oferta (si está asociado a una oferta)
- ✅ Los mensajes se ordenan por fecha de creación (más antiguos primero)

### Estados de Ofertas:
- `PENDIENTE`: La oferta está esperando respuesta
- `ACEPTADA`: El vendedor aceptó la oferta
- `RECHAZADA`: El vendedor rechazó la oferta
- `CANCELADA`: El ofertante canceló la oferta

---

## 🚀 Próximos Pasos

1. Ejecutar las migraciones:
   ```bash
   docker compose exec app php artisan migrate
   ```

2. Probar las APIs con Postman, cURL o cualquier cliente HTTP

3. Integrar en tu aplicación frontend

---

## 📞 Soporte

Si tienes problemas o preguntas sobre estas APIs, revisa:
- Los logs del servidor
- La documentación de Laravel
- El código fuente en `app/Presentation/Http/Controllers/OfertaController.php` y `MensajeController.php`

