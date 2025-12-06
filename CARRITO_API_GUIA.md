# 🛒 Guía Completa de API de Carrito de Compras

## 📋 Índice
1. [Requisitos Previos](#requisitos-previos)
2. [Endpoints Disponibles](#endpoints-disponibles)
3. [Ejemplos Detallados](#ejemplos-detallados)
4. [Flujo Completo de Uso](#flujo-completo-de-uso)

---

## 🔐 Requisitos Previos

**IMPORTANTE**: Todas las rutas requieren autenticación JWT. Primero debes obtener un token:

### 1. Obtener Token JWT

```bash
POST http://localhost:8080/api/auth/login
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

## 📡 Endpoints Disponibles

### 1. **GET** `/api/carrito` - Obtener carrito completo
### 2. **POST** `/api/carrito` - Agregar producto al carrito
### 3. **PUT** `/api/carrito/{id}` - Actualizar cantidad
### 4. **DELETE** `/api/carrito/{id}` - Eliminar item del carrito
### 5. **POST** `/api/carrito/limpiar` - Limpiar todo el carrito

---

## 📝 Ejemplos Detallados

### 1️⃣ AGREGAR PRODUCTO AL CARRITO

**Endpoint:** `POST /api/carrito`

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "id_stock": 1,
  "cantidad": 5
}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/carrito \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "id_stock": 1,
    "cantidad": 5
  }'
```

**Respuesta Exitosa (201):**
```json
{
  "status": true,
  "code": 201,
  "data": {
    "id_carrito": 1,
    "id_usuario": 3,
    "id_stock": 1,
    "cantidad": 5
  },
  "title": "Item agregado",
  "message": "Item agregado al carrito exitosamente"
}
```

**Si el producto ya existe en el carrito:**
- Automáticamente **suma** la cantidad nueva a la existente
- Ejemplo: Si tienes 3 y agregas 5, ahora tendrás 8

**Errores Posibles:**

**Stock no encontrado (422):**
```json
{
  "status": false,
  "code": 422,
  "message": "Error de validación",
  "otherData": {
    "errors": {
      "id_stock": ["El campo id_stock no existe en stock"]
    }
  }
}
```

**Stock insuficiente (500):**
```json
{
  "status": false,
  "code": 500,
  "message": "No hay suficiente stock disponible. Disponible: 3, solicitado: 5"
}
```

---

### 2️⃣ VER CARRITO COMPLETO

**Endpoint:** `GET /api/carrito`

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
```

**Ejemplo con cURL:**
```bash
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_carrito": 1,
      "id_usuario": 3,
      "id_stock": 1,
      "cantidad": 5,
      "stock": {
        "id_stock": 1,
        "precio": 10.50,
        "cantidad": 100,
        "cantidad_disponible": 95,
        "cantidad_vendida": 5,
        "tipo_moneda": "PEN",
        "recibe_ofertas": false,
        "unidad": {
          "id_unidad": 6,
          "nombre": "Kilogramos",
          "simbolo": "kg"
        },
        "producto": {
          "id_producto": 1,
          "nombre": "Semilla de Papa Amarilla",
          "descripcion": "Semilla certificada",
          "imagen": "/prouctos/producto1.jpg",
          "estado": "ACTIVO"
        }
      }
    },
    {
      "id_carrito": 2,
      "id_usuario": 3,
      "id_stock": 5,
      "cantidad": 10,
      "stock": {
        "id_stock": 5,
        "precio": 25.00,
        "cantidad": 50,
        "cantidad_disponible": 40,
        "cantidad_vendida": 10,
        "tipo_moneda": "USD",
        "recibe_ofertas": true,
        "unidad": {
          "id_unidad": 7,
          "nombre": "Litros",
          "simbolo": "L"
        },
        "producto": {
          "id_producto": 5,
          "nombre": "Fertilizante Orgánico",
          "descripcion": "100% orgánico",
          "imagen": "/prouctos/producto5.jpg",
          "estado": "ACTIVO"
        }
      }
    }
  ],
  "title": "Carrito de compras",
  "message": "Carrito obtenido exitosamente"
}
```

**Carrito vacío:**
```json
{
  "status": true,
  "code": 200,
  "data": [],
  "title": "Carrito de compras",
  "message": "Carrito obtenido exitosamente"
}
```

---

### 3️⃣ ACTUALIZAR CANTIDAD (Aumentar/Disminuir)

**Endpoint:** `PUT /api/carrito/{id_carrito}`

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "cantidad": 10
}
```

**Ejemplo con cURL:**
```bash
curl -X PUT http://localhost:8080/api/carrito/1 \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "cantidad": 10
  }'
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "id_carrito": 1,
    "id_usuario": 3,
    "id_stock": 1,
    "cantidad": 10
  },
  "title": "Item actualizado",
  "message": "Cantidad actualizada exitosamente"
}
```

**Si pones cantidad = 0:**
- Automáticamente **elimina** el item del carrito
- Respuesta:
```json
{
  "status": true,
  "code": 200,
  "data": null,
  "title": "Item actualizado",
  "message": "Item eliminado del carrito (cantidad 0)"
}
```

**Error - Stock insuficiente:**
```json
{
  "status": false,
  "code": 500,
  "message": "No hay suficiente stock disponible. Disponible: 8, solicitado: 10"
}
```

---

### 4️⃣ ELIMINAR ITEM DEL CARRITO

**Endpoint:** `DELETE /api/carrito/{id_carrito}`

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
```

**Ejemplo con cURL:**
```bash
curl -X DELETE http://localhost:8080/api/carrito/1 \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": null,
  "title": "Item eliminado",
  "message": "Item eliminado del carrito exitosamente"
}
```

**Error - Item no encontrado (404):**
```json
{
  "status": false,
  "code": 404,
  "message": "Item no encontrado en el carrito"
}
```

---

### 5️⃣ LIMPIAR TODO EL CARRITO

**Endpoint:** `POST /api/carrito/limpiar`

**Headers:**
```
Authorization: Bearer TU_TOKEN_JWT
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8080/api/carrito/limpiar \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

**Respuesta Exitosa (200):**
```json
{
  "status": true,
  "code": 200,
  "data": null,
  "title": "Carrito limpiado",
  "message": "Carrito limpiado exitosamente"
}
```

---

## 🔄 Flujo Completo de Uso

### Escenario: Usuario compra productos

**Paso 1: Login y obtener token**
```bash
POST /api/auth/login
{
  "usuario": "nilsen",
  "clave": "123456"
}
# Guardar el token
```

**Paso 2: Buscar productos disponibles**
```bash
GET /api/productos/subcategoria/20
Authorization: Bearer TOKEN
```

**Paso 3: Agregar primer producto al carrito**
```bash
POST /api/carrito
Authorization: Bearer TOKEN
{
  "id_stock": 1,
  "cantidad": 5
}
```

**Paso 4: Agregar segundo producto al carrito**
```bash
POST /api/carrito
Authorization: Bearer TOKEN
{
  "id_stock": 5,
  "cantidad": 10
}
```

**Paso 5: Ver carrito completo**
```bash
GET /api/carrito
Authorization: Bearer TOKEN
```

**Paso 6: Aumentar cantidad del primer producto**
```bash
PUT /api/carrito/1
Authorization: Bearer TOKEN
{
  "cantidad": 8
}
```

**Paso 7: Disminuir cantidad del segundo producto**
```bash
PUT /api/carrito/2
Authorization: Bearer TOKEN
{
  "cantidad": 7
}
```

**Paso 8: Eliminar un producto del carrito**
```bash
DELETE /api/carrito/2
Authorization: Bearer TOKEN
```

**Paso 9: Ver carrito actualizado**
```bash
GET /api/carrito
Authorization: Bearer TOKEN
```

---

## 📱 Ejemplos para Aplicación Móvil

### React Native / Flutter / JavaScript

```javascript
const API_BASE = 'http://192.168.101.9:8080/api';
const token = 'TU_TOKEN_JWT';

// 1. Agregar al carrito
const agregarAlCarrito = async (idStock, cantidad) => {
  const response = await fetch(`${API_BASE}/carrito`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      id_stock: idStock,
      cantidad: cantidad
    })
  });
  return await response.json();
};

// 2. Ver carrito
const obtenerCarrito = async () => {
  const response = await fetch(`${API_BASE}/carrito`, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`,
    }
  });
  return await response.json();
};

// 3. Actualizar cantidad
const actualizarCantidad = async (idCarrito, nuevaCantidad) => {
  const response = await fetch(`${API_BASE}/carrito/${idCarrito}`, {
    method: 'PUT',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      cantidad: nuevaCantidad
    })
  });
  return await response.json();
};

// 4. Eliminar item
const eliminarItem = async (idCarrito) => {
  const response = await fetch(`${API_BASE}/carrito/${idCarrito}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`,
    }
  });
  return await response.json();
};

// 5. Limpiar carrito
const limpiarCarrito = async () => {
  const response = await fetch(`${API_BASE}/carrito/limpiar`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    }
  });
  return await response.json();
};
```

---

## ⚠️ Validaciones Importantes

1. **Stock debe existir**: El `id_stock` debe existir en la tabla `stock`
2. **Cantidad mínima**: La cantidad debe ser al menos 1
3. **Stock disponible**: No se puede agregar más cantidad de la disponible
4. **Autenticación**: Todas las rutas requieren token JWT válido
5. **Usuario**: Solo puedes ver/modificar tu propio carrito

---

## 🔍 Cómo Obtener el ID del Stock

Para agregar un producto al carrito, necesitas el `id_stock`. Puedes obtenerlo de:

1. **API de productos por subcategoría:**
   ```bash
   GET /api/productos/subcategoria/20
   ```
   En la respuesta, cada producto tiene un objeto `stock` con el `id_stock`

2. **API de stock por producto:**
   ```bash
   GET /api/stocks/producto/1
   ```

---

## 📊 Resumen de Respuestas

| Método | Endpoint | Éxito | Error |
|--------|----------|-------|-------|
| GET | `/api/carrito` | 200 | 401, 500 |
| POST | `/api/carrito` | 201 | 401, 422, 500 |
| PUT | `/api/carrito/{id}` | 200 | 401, 404, 422, 500 |
| DELETE | `/api/carrito/{id}` | 200 | 401, 404, 500 |
| POST | `/api/carrito/limpiar` | 200 | 401, 500 |

---

## 💡 Tips

1. **Guarda el token** después del login
2. **Verifica el stock disponible** antes de agregar al carrito
3. **Maneja errores** de stock insuficiente
4. **Actualiza el carrito** después de cada operación
5. **Valida cantidades** antes de enviar al servidor

---

¿Necesitas ayuda con algo más? 🚀

