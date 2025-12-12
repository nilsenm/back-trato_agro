# 🛒 API para Obtener el Carrito

## 🔐 Autenticación

**IMPORTANTE**: Esta ruta requiere autenticación JWT. Primero debes obtener un token:

```bash
POST http://localhost:8080/api/auth/login
Content-Type: application/json

{
  "usuario": "tu_usuario",
  "clave": "tu_clave"
}
```

Usa el token en todos los requests:
```
Authorization: Bearer TU_TOKEN_JWT
```

---

## 📡 Endpoint

**Método:** `GET`  
**URL:** `/api/carrito`

Obtiene todos los items del carrito del usuario autenticado con información completa de productos, stock y unidades.

---

## 💻 Ejemplos

### Ejemplo con cURL:

```bash
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

### Ejemplo con JavaScript/Fetch:

```javascript
fetch('http://localhost:8080/api/carrito', {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## ✅ Respuesta Exitosa (200)

```json
{
  "status": true,
  "code": 200,
  "data": [
    {
      "id_carrito": 1,
      "id_usuario": 5,
      "id_stock": 5,
      "cantidad": 10,
      "stock": {
        "id_stock": 5,
        "precio": 25.50,
        "cantidad": 50,
        "cantidad_disponible": 40,
        "cantidad_vendida": 10,
        "tipo_moneda": "PEN",
        "recibe_ofertas": true,
        "destacado": false,
        "unidad": {
          "id_unidad": 1,
          "nombre": "Kilogramo (kg)"
        },
        "producto": {
          "id_producto": 10,
          "nombre": "Maíz Amarillo",
          "descripcion": "Maíz de alta calidad",
          "imagen": "https://example.com/maiz.jpg",
          "id_subcategoria": 20,
          "id_usuario": 2,
          "estado": "ACTIVO"
        }
      }
    },
    {
      "id_carrito": 2,
      "id_usuario": 5,
      "id_stock": 8,
      "cantidad": 5,
      "stock": {
        "id_stock": 8,
        "precio": 15.00,
        "cantidad": 30,
        "cantidad_disponible": 30,
        "cantidad_vendida": 0,
        "tipo_moneda": "PEN",
        "recibe_ofertas": false,
        "destacado": false,
        "unidad": {
          "id_unidad": 1,
          "nombre": "Kilogramo (kg)"
        },
        "producto": {
          "id_producto": 12,
          "nombre": "Arroz",
          "descripcion": "Arroz premium",
          "imagen": "https://example.com/arroz.jpg",
          "id_subcategoria": 21,
          "id_usuario": 3,
          "estado": "ACTIVO"
        }
      }
    }
  ],
  "title": "Carrito de compras",
  "message": "Carrito obtenido exitosamente"
}
```

---

## 📊 Información Incluida

Cada item del carrito incluye:

### Información del Item:
- `id_carrito`: ID del item en el carrito
- `id_usuario`: ID del usuario propietario del carrito
- `id_stock`: ID del stock
- `cantidad`: Cantidad en el carrito

### Información del Stock:
- `id_stock`: ID del stock
- `precio`: Precio unitario
- `cantidad`: Cantidad total en stock
- `cantidad_disponible`: Cantidad disponible (total - vendida)
- `cantidad_vendida`: Cantidad ya vendida
- `tipo_moneda`: Moneda (PEN/USD)
- `recibe_ofertas`: Si acepta ofertas
- `destacado`: Si está destacado
- `unidad`: Información de la unidad de medida
- `producto`: Información completa del producto

---

## ❌ Errores Posibles

### 401 - No autenticado

```json
{
  "status": false,
  "code": 401,
  "message": "Usuario no autenticado",
  "title": "Error de autenticación"
}
```

### 500 - Error del servidor

```json
{
  "status": false,
  "code": 500,
  "message": "Error del servidor",
  "title": "Error del servidor"
}
```

---

## 📝 Notas Importantes

1. **Solo muestra el carrito del usuario autenticado** (obtenido del token JWT)
2. **Incluye información completa** de cada producto (nombre, descripción, imagen)
3. **Muestra stock disponible** calculado automáticamente
4. **Incluye información de la unidad** de medida
5. **Si el carrito está vacío**, devuelve un array vacío `[]`

---

## 🎯 Resumen

- **Endpoint**: `GET /api/carrito`
- **Autenticación**: Requerida (JWT)
- **Respuesta**: Array de items del carrito con información completa
- **Información incluida**: Producto, stock, unidad, cantidades disponibles

---

## 💡 Ejemplo de Uso Completo

```bash
# 1. Obtener token
TOKEN=$(curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"usuario":"tu_usuario","clave":"tu_clave"}' \
  | jq -r '.data.token')

# 2. Obtener carrito
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN"

# 3. Agregar producto al carrito
curl -X POST http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "id_stock": 5,
    "cantidad": 10
  }'

# 4. Ver carrito actualizado
curl -X GET http://localhost:8080/api/carrito \
  -H "Authorization: Bearer $TOKEN"
```



