# 🛒 API para Finalizar Venta desde el Carrito

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

**Método:** `POST`  
**URL:** `/api/carrito/finalizar-venta`

Este endpoint:
1. ✅ Valida que el carrito tenga items
2. ✅ Verifica stock disponible para cada producto
3. ✅ Calcula el resumen de la compra (subtotal, total)
4. ✅ Crea la venta con la información de envío
5. ✅ Crea los detalles de venta para cada item
6. ✅ Limpia el carrito automáticamente
7. ✅ Devuelve el resumen completo de la venta

---

## 📋 Datos Necesarios

### Campos Requeridos:

| Campo | Tipo | Validación | Descripción |
|-------|------|------------|-------------|
| `id_distrito` | integer | debe existir en distrito | ID del distrito de entrega |

### Campos Opcionales:

| Campo | Tipo | Validación | Descripción |
|-------|------|------------|-------------|
| `direccion` | string | max:500 | Dirección específica (opcional) |
| `tipo_pago` | string | CONTRA_ENTREGA | Tipo de pago (por ahora solo contra entrega) |

**Nota:** Solo necesitas enviar el `id_distrito` porque el sistema ya tiene concatenada la información de departamento, provincia y distrito.

---

## 💻 Ejemplos

### Ejemplo 1: Finalizar venta básica (solo id_distrito)

**cURL:**
```bash
curl -X POST http://localhost:8080/api/carrito/finalizar-venta \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "id_distrito": 1
  }'
```

**JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/carrito/finalizar-venta', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    id_distrito: 1
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

### Ejemplo 2: Finalizar venta con dirección

**cURL:**
```bash
curl -X POST http://localhost:8080/api/carrito/finalizar-venta \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "id_distrito": 1,
    "direccion": "Calle Los Olivos 123, Mz A Lt 5"
  }'
```

**JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/carrito/finalizar-venta', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    id_distrito: 1,
    direccion: 'Calle Los Olivos 123, Mz A Lt 5'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

### Ejemplo 3: Finalizar venta completa

**cURL:**
```bash
curl -X POST http://localhost:8080/api/carrito/finalizar-venta \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "id_distrito": 1,
    "direccion": "Calle Los Olivos 123, Mz A Lt 5",
    "tipo_pago": "CONTRA_ENTREGA"
  }'
```

**JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/carrito/finalizar-venta', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    id_distrito: 1,
    direccion: 'Calle Los Olivos 123, Mz A Lt 5',
    tipo_pago: 'CONTRA_ENTREGA'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## ✅ Respuesta Exitosa (201)

```json
{
  "status": true,
  "code": 201,
  "data": {
    "venta": {
      "id_venta": 1,
      "fecha": "2025-12-06",
      "hora": "12:30:00",
      "id_usuario_compra": 5,
      "id_distrito": 1,
      "direccion": "Calle Los Olivos 123, Mz A Lt 5",
      "tipo_pago": "CONTRA_ENTREGA",
      "estado": "PEDIDO"
    },
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
    "items": [
      {
        "id_carrito": 1,
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
        "id_carrito": 2,
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
    },
    "detalles_venta": [
      {
        "id_detalle_venta": 1,
        "cantidad": 10,
        "id_stock": 5,
        "id_venta": 1
      },
      {
        "id_detalle_venta": 2,
        "cantidad": 5,
        "id_stock": 8,
        "id_venta": 1
      }
    ]
  },
  "title": "Venta finalizada",
  "message": "Venta finalizada exitosamente"
}
```

---

## ❌ Errores Posibles

### 400 - Carrito vacío

```json
{
  "status": false,
  "code": 400,
  "message": "El carrito está vacío",
  "title": "Carrito vacío"
}
```

### 400 - Stock insuficiente

```json
{
  "status": false,
  "code": 500,
  "message": "No hay suficiente stock disponible para uno de los productos. Disponible: 5, solicitado: 10",
  "title": "Error del servidor"
}
```

### 401 - No autenticado

```json
{
  "status": false,
  "code": 401,
  "message": "Usuario no autenticado",
  "title": "Error de autenticación"
}
```

### 422 - Error de validación

```json
{
  "status": false,
  "code": 422,
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "id_distrito": [
      "El campo id_distrito seleccionado no es válido."
    ]
  },
  "title": "Error de validación"
}
```

---

## 📊 Estructura de la Respuesta

### `venta`
Información de la venta creada:
- `id_venta`: ID de la venta
- `fecha`: Fecha de la venta
- `hora`: Hora de la venta
- `id_usuario_compra`: ID del usuario comprador
- `id_distrito`: ID del distrito de entrega
- `direccion`: Dirección específica (si se proporcionó)
- `tipo_pago`: Tipo de pago (CONTRA_ENTREGA)
- `estado`: Estado de la venta (PEDIDO, ENVIADO, ENTREGADO)

### `comprador`
Información del comprador:
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

### `items`
Array con cada producto del carrito:
- `id_carrito`: ID del item del carrito
- `id_stock`: ID del stock
- `cantidad`: Cantidad comprada
- `precio_unitario`: Precio por unidad
- `subtotal`: Subtotal del item (precio × cantidad)
- `tipo_moneda`: Moneda (PEN/USD)
- `producto`: Información del producto (nombre, descripción, imagen)
- `unidad`: Información de la unidad de medida
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

### `resumen`
Resumen de la compra:
- `subtotal`: Suma de todos los subtotales
- `total`: Monto total a pagar
- `tipo_moneda`: Moneda utilizada
- `cantidad_items`: Número de items en la compra

### `detalles_venta`
Array con los detalles de venta creados (uno por cada item)

---

## 🔄 Flujo Completo

1. **Agregar productos al carrito:**
   ```bash
   POST /api/carrito
   {
     "id_stock": 5,
     "cantidad": 10
   }
   ```

2. **Ver carrito:**
   ```bash
   GET /api/carrito
   ```

3. **Finalizar venta:**
   ```bash
   POST /api/carrito/finalizar-venta
   {
     "id_distrito": 1,
     "direccion": "Calle Los Olivos 123",
     "tipo_pago": "CONTRA_ENTREGA"
   }
   ```

4. **El carrito se limpia automáticamente** después de finalizar la venta

---

## 📝 Notas Importantes

1. **El carrito se limpia automáticamente** después de finalizar la venta
2. **Solo necesitas enviar `id_distrito`** - el sistema ya tiene la información de departamento, provincia y distrito concatenada
3. **Se valida el stock disponible** antes de crear la venta
4. **Si falta stock**, la venta no se crea y recibes un error
5. **El tipo de pago por defecto es `CONTRA_ENTREGA`** si no se especifica
6. **La dirección es opcional** - puedes enviarla o no
7. **El resumen incluye todos los cálculos** (subtotal, total, cantidad de items)
8. **El estado por defecto es `PEDIDO`** - puede cambiar a `ENVIADO` o `ENTREGADO` posteriormente
9. **La respuesta incluye información completa** del comprador y vendedores:
   - Nombre del usuario
   - Nombre del cliente (persona natural o jurídica)
   - DNI o RUC según corresponda
   - Información completa de cada vendedor por producto

---

## 🎯 Resumen

- **Endpoint**: `POST /api/carrito/finalizar-venta`
- **Autenticación**: Requerida (JWT)
- **Campos requeridos**: `id_distrito`
- **Campos opcionales**: `direccion`, `tipo_pago`
- **Estado por defecto**: `PEDIDO` (puede ser `ENVIADO` o `ENTREGADO`)
- **Acciones automáticas**: 
  - Crea la venta con estado `PEDIDO`
  - Crea los detalles de venta
  - Limpia el carrito
  - Calcula y devuelve el resumen completo
  - Incluye información del comprador (usuario y persona)
  - Incluye información de cada vendedor por producto
  - Incluye DNI o RUC según corresponda

