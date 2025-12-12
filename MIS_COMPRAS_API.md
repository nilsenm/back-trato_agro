# 🛒 API para Listar Mis Compras Detalladas

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
**URL:** `/api/ventas/mis-compras`

Obtiene todas las compras del usuario autenticado con información detallada de cada compra, incluyendo productos, vendedores, precios y resumen.

---

## 💻 Ejemplos

### Ejemplo con cURL:

```bash
curl -X GET http://localhost:8080/api/ventas/mis-compras \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

### Ejemplo con JavaScript/Fetch:

```javascript
fetch('http://localhost:8080/api/ventas/mis-compras', {
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
  "data": {
    "comprador": {
      "id_usuario": 5,
      "nombre": "Juan Pérez",
      "username": "juanperez",
      "correo": "juan@example.com",
      "documento": "73493357",
      "tipo_persona": "N",
      "persona": {
        "tipo": "NATURAL",
        "dni": "73493357",
        "nombres": "Juan",
        "apellidos": "Pérez",
        "nombre_completo": "Juan Pérez",
        "documento": "73493357",
        "tipo_documento": "DNI"
      }
    },
    "compras": [
      {
        "id_venta": 1,
        "fecha": "2025-12-06",
        "hora": "12:30:00",
        "id_usuario_compra": 5,
        "id_distrito": 1,
        "estado": "PEDIDO",
        "items": [
          {
            "id_detalle_venta": 1,
            "id_stock": 5,
            "cantidad": 10,
            "precio_unitario": 25.50,
            "subtotal": 255.00,
            "tipo_moneda": "PEN",
            "producto": {
              "id_producto": 10,
              "nombre": "Maíz Amarillo",
              "descripcion": "Maíz de alta calidad",
              "imagen": "https://example.com/maiz.jpg"
            },
            "unidad": {
              "id_unidad": 1,
              "nombre": "Kilogramo (kg)"
            },
            "vendedor": {
              "id_usuario": 2,
              "nombre": "María García",
              "username": "mariagarcia",
              "correo": "maria@example.com",
              "documento": "12345678",
              "tipo_persona": "N",
              "persona": {
                "tipo": "NATURAL",
                "dni": "12345678",
                "nombres": "María",
                "apellidos": "García",
                "documento": "12345678",
                "tipo_documento": "DNI"
              }
            }
          },
          {
            "id_detalle_venta": 2,
            "id_stock": 8,
            "cantidad": 5,
            "precio_unitario": 15.00,
            "subtotal": 75.00,
            "tipo_moneda": "PEN",
            "producto": {
              "id_producto": 12,
              "nombre": "Arroz",
              "descripcion": "Arroz premium",
              "imagen": "https://example.com/arroz.jpg"
            },
            "unidad": {
              "id_unidad": 1,
              "nombre": "Kilogramo (kg)"
            },
            "vendedor": {
              "id_usuario": 3,
              "nombre": "Empresa ABC S.A.C.",
              "username": "empresaabc",
              "correo": "contacto@empresaabc.com",
              "documento": "20123456789",
              "tipo_persona": "J",
              "persona": {
                "tipo": "JURIDICA",
                "ruc": "20123456789",
                "razon_social": "Empresa ABC S.A.C.",
                "documento": "20123456789",
                "tipo_documento": "RUC"
              }
            }
          }
        ],
        "resumen": {
          "subtotal": 330.00,
          "total": 330.00,
          "tipo_moneda": "PEN",
          "cantidad_items": 2
        }
      },
      {
        "id_venta": 2,
        "fecha": "2025-12-05",
        "hora": "10:15:00",
        "id_usuario_compra": 5,
        "id_distrito": 2,
        "estado": "ENVIADO",
        "items": [
          {
            "id_detalle_venta": 3,
            "id_stock": 10,
            "cantidad": 3,
            "precio_unitario": 30.00,
            "subtotal": 90.00,
            "tipo_moneda": "PEN",
            "producto": {
              "id_producto": 15,
              "nombre": "Trigo",
              "descripcion": "Trigo de primera calidad",
              "imagen": "https://example.com/trigo.jpg"
            },
            "unidad": {
              "id_unidad": 1,
              "nombre": "Kilogramo (kg)"
            },
            "vendedor": {
              "id_usuario": 4,
              "nombre": "Carlos López",
              "username": "carloslopez",
              "correo": "carlos@example.com",
              "documento": "87654321",
              "tipo_persona": "N",
              "persona": {
                "tipo": "NATURAL",
                "dni": "87654321",
                "nombres": "Carlos",
                "apellidos": "López",
                "documento": "87654321",
                "tipo_documento": "DNI"
              }
            }
          }
        ],
        "resumen": {
          "subtotal": 90.00,
          "total": 90.00,
          "tipo_moneda": "PEN",
          "cantidad_items": 1
        }
      }
    ],
    "total_compras": 2
  },
  "title": "Mis compras",
  "message": "Compras obtenidas exitosamente"
}
```

---

## 📊 Estructura de la Respuesta

### `comprador`
Información del comprador (usuario autenticado):
- `id_usuario`: ID del usuario
- `nombre`: Nombre del usuario
- `username`: Username del usuario
- `correo`: Correo electrónico
- `documento`: Documento (DNI o RUC)
- `tipo_persona`: Tipo de persona (N=Natural, J=Jurídica)
- `persona`: Información de la persona (natural o jurídica)
  - `tipo`: NATURAL o JURIDICA
  - `dni` o `ruc`: Documento de identidad
  - `nombres` y `apellidos` (si es natural) o `razon_social` (si es jurídica)
  - `nombre_completo`: Nombre completo del cliente
  - `documento`: DNI o RUC
  - `tipo_documento`: DNI o RUC

### `compras`
Array con todas las compras del usuario, ordenadas por fecha más reciente primero.

Cada compra incluye:

#### Información de la Venta:
- `id_venta`: ID de la venta
- `fecha`: Fecha de la venta
- `hora`: Hora de la venta
- `id_usuario_compra`: ID del usuario comprador
- `id_distrito`: ID del distrito de entrega
- `estado`: Estado de la venta (PEDIDO, ENVIADO, ENTREGADO)

#### `items`
Array con cada producto comprado:
- `id_detalle_venta`: ID del detalle de venta
- `id_stock`: ID del stock
- `cantidad`: Cantidad comprada
- `precio_unitario`: Precio por unidad al momento de la compra
- `subtotal`: Subtotal del item (precio × cantidad)
- `tipo_moneda`: Moneda (PEN/USD)
- `producto`: Información del producto
  - `id_producto`: ID del producto
  - `nombre`: Nombre del producto
  - `descripcion`: Descripción del producto
  - `imagen`: URL de la imagen
- `unidad`: Información de la unidad de medida
  - `id_unidad`: ID de la unidad
  - `nombre`: Nombre de la unidad
- `vendedor`: Información del vendedor (dueño del producto)
  - `id_usuario`: ID del usuario vendedor
  - `nombre`: Nombre del vendedor
  - `username`: Username del vendedor
  - `correo`: Correo electrónico
  - `documento`: Documento (DNI o RUC)
  - `tipo_persona`: Tipo de persona (N=Natural, J=Jurídica)
  - `persona`: Información de la persona (natural o jurídica)
    - `tipo`: NATURAL o JURIDICA
    - `dni` o `ruc`: Documento de identidad
    - `nombres` y `apellidos` (si es natural) o `razon_social` (si es jurídica)
    - `documento`: DNI o RUC
    - `tipo_documento`: DNI o RUC

#### `resumen`
Resumen de cada compra:
- `subtotal`: Suma de todos los subtotales
- `total`: Monto total de la compra
- `tipo_moneda`: Moneda utilizada
- `cantidad_items`: Número de items en la compra

### `total_compras`
Número total de compras del usuario

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

### 200 - Sin compras

```json
{
  "status": true,
  "code": 200,
  "data": {
    "comprador": { ... },
    "compras": [],
    "total_compras": 0
  },
  "title": "Mis compras",
  "message": "No se encontraron compras"
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

1. **Solo muestra las compras del usuario autenticado** (obtenido del token JWT)
2. **Las compras están ordenadas** por fecha más reciente primero
3. **Incluye información completa** de cada producto comprado
4. **Muestra información de cada vendedor** por producto
5. **Incluye DNI o RUC** del comprador y vendedores según corresponda
6. **Muestra el estado** de cada compra (PEDIDO, ENVIADO, ENTREGADO)
7. **Incluye resumen** de cada compra (subtotal, total, cantidad de items)
8. **Si no hay compras**, devuelve un array vacío `[]`

---

## 🎯 Resumen

- **Endpoint**: `GET /api/ventas/mis-compras`
- **Autenticación**: Requerida (JWT)
- **Respuesta**: Array de compras con información detallada
- **Información incluida**: 
  - Información del comprador
  - Información de cada venta
  - Productos comprados con detalles
  - Información de cada vendedor
  - Resumen de cada compra
  - Estado de cada compra

---

## 💡 Ejemplo de Uso Completo

```bash
# 1. Obtener token
TOKEN=$(curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"usuario":"tu_usuario","clave":"tu_clave"}' \
  | jq -r '.data.token')

# 2. Obtener mis compras
curl -X GET http://localhost:8080/api/ventas/mis-compras \
  -H "Authorization: Bearer $TOKEN" | jq
```

---

## 🔄 Estados de la Venta

Los estados posibles son:
- **PEDIDO**: La compra ha sido realizada pero aún no ha sido enviada
- **ENVIADO**: La compra ha sido enviada al comprador
- **ENTREGADO**: La compra ha sido entregada al comprador



