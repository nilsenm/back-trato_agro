# 🛒 Ejemplos Rápidos - API de Carrito

## 🚀 Configuración Inicial

**Base URL:** `http://localhost:8080/api` (o `http://192.168.101.9:8080/api`)

**Variables importantes:**
- `TOKEN`: Tu token JWT (obtenlo con login)
- `ID_STOCK`: El ID del stock que quieres agregar
- `ID_CARRITO`: El ID del item en el carrito

---

## 1️⃣ LOGIN - Obtener Token

```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "usuario": "nilsen",
    "clave": "123456"
  }'
```

**Guarda el token de la respuesta para los siguientes pasos**

---

## 2️⃣ AGREGAR PRODUCTO AL CARRITO

```bash
# Reemplaza TU_TOKEN con el token que obtuviste
# Reemplaza ID_STOCK con el ID real del stock

curl -X POST http://localhost:8080/api/carrito \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "id_stock": 1,
    "cantidad": 5
  }'
```

**Ejemplo con valores reales:**
```bash
curl -X POST http://localhost:8080/api/carrito \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTkyLjE2OC4xMDEuOTo4MDgwL2FwaS9hdXRoL2xvZ2luIiwiaWF0IjoxNzY0OTEwMjA0LCJleHAiOjE3NjQ5MTM4MDQsIm5iZiI6MTc2NDkxMDIwNCwianRpIjoiQ2hmRTNXcDZwNVk1c2lyUiIsInN1YiI6IjIiLCJwcnYiOiIxMTM4NmRkZWJmMmZmM2VlODA3Njg5OGU4YjY4YWNhOGVmY2E4NWNkIn0.JUDaVJfq5IyJm-6bJjdjCRt9YoicsCUd7bT6DK174T0" \
  -H "Content-Type: application/json" \
  -d '{
    "id_stock": 1,
    "cantidad": 5
  }'
```

---

## 3️⃣ VER CARRITO COMPLETO

```bash
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer TU_TOKEN"
```

---

## 4️⃣ AUMENTAR CANTIDAD

```bash
# Reemplaza ID_CARRITO con el ID del item del carrito
# Ejemplo: Si el carrito tiene id_carrito: 1, cambia {id} por 1

curl -X PUT http://localhost:8080/api/carrito/1 \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "cantidad": 10
  }'
```

---

## 5️⃣ DISMINUIR CANTIDAD

```bash
# Mismo endpoint, solo cambia la cantidad
curl -X PUT http://localhost:8080/api/carrito/1 \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "cantidad": 3
  }'
```

---

## 6️⃣ ELIMINAR UN PRODUCTO DEL CARRITO

```bash
curl -X DELETE http://localhost:8080/api/carrito/1 \
  -H "Authorization: Bearer TU_TOKEN"
```

---

## 7️⃣ LIMPIAR TODO EL CARRITO

```bash
curl -X POST http://localhost:8080/api/carrito/limpiar \
  -H "Authorization: Bearer TU_TOKEN"
```

---

## 📱 Ejemplos para Postman / Insomnia

### Agregar al Carrito
```
POST http://localhost:8080/api/carrito
Headers:
  Authorization: Bearer TU_TOKEN
  Content-Type: application/json

Body (JSON):
{
  "id_stock": 1,
  "cantidad": 5
}
```

### Ver Carrito
```
GET http://localhost:8080/api/carrito
Headers:
  Authorization: Bearer TU_TOKEN
```

### Actualizar Cantidad
```
PUT http://localhost:8080/api/carrito/1
Headers:
  Authorization: Bearer TU_TOKEN
  Content-Type: application/json

Body (JSON):
{
  "cantidad": 10
}
```

### Eliminar Item
```
DELETE http://localhost:8080/api/carrito/1
Headers:
  Authorization: Bearer TU_TOKEN
```

### Limpiar Carrito
```
POST http://localhost:8080/api/carrito/limpiar
Headers:
  Authorization: Bearer TU_TOKEN
```

---

## 🔍 Cómo Obtener el ID del Stock

**Opción 1: Desde productos por subcategoría**
```bash
GET http://localhost:8080/api/productos/subcategoria/20
Authorization: Bearer TU_TOKEN
```

En la respuesta, cada producto tiene:
```json
{
  "stock": {
    "id_stock": 1,  // <-- Este es el ID que necesitas
    "precio": 10.50,
    "cantidad": 100,
    ...
  }
}
```

**Opción 2: Desde stocks por producto**
```bash
GET http://localhost:8080/api/stocks/producto/1
Authorization: Bearer TU_TOKEN
```

---

## 🎯 Flujo Completo Ejemplo

```bash
# 1. Login
TOKEN=$(curl -s -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"usuario":"nilsen","clave":"123456"}' | jq -r '.data.token')

echo "Token obtenido: $TOKEN"

# 2. Ver productos disponibles
curl -X GET "http://localhost:8080/api/productos/subcategoria/20" \
  -H "Authorization: Bearer $TOKEN"

# 3. Agregar producto 1 al carrito
curl -X POST http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"id_stock": 1, "cantidad": 5}'

# 4. Agregar producto 2 al carrito
curl -X POST http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"id_stock": 2, "cantidad": 10}'

# 5. Ver carrito completo
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN"

# 6. Aumentar cantidad del primer producto
curl -X PUT http://localhost:8080/api/carrito/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"cantidad": 8}'

# 7. Ver carrito actualizado
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN"
```

---

## ✅ Respuestas Esperadas

### Agregar al Carrito (Éxito)
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
  "message": "Item agregado al carrito exitosamente"
}
```

### Ver Carrito (Éxito)
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
        "cantidad_disponible": 95,
        "producto": {...}
      }
    }
  ],
  "message": "Carrito obtenido exitosamente"
}
```

### Error - Stock Insuficiente
```json
{
  "status": false,
  "code": 500,
  "message": "No hay suficiente stock disponible. Disponible: 3, solicitado: 5"
}
```

---

¿Listo para probar? 🚀

