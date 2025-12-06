# 📝 API para Editar Producto

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

## 📡 Endpoint

**Método:** `PUT`  
**URL:** `/api/productos/{id}`

Donde `{id}` es el ID del producto que quieres editar.

---

## 📋 Datos Necesarios

### Campos Opcionales (puedes enviar solo los que quieras actualizar):

#### 📦 Campos del Producto:

| Campo | Tipo | Validación | Descripción |
|-------|------|------------|-------------|
| `nombre` | string | max:300 | Nombre del producto |
| `descripcion` | string | opcional | Descripción del producto |
| `imagen` | string | opcional | Imagen del producto (base64, URL o ruta) |
| `id_subcategoria` | integer | debe existir en subcategoria | ID de la subcategoría |
| `estado` | string | ACTIVO o INACTIVO | Estado del producto |

#### 📊 Campos del Stock:

| Campo | Tipo | Validación | Descripción |
|-------|------|------------|-------------|
| `precio` | number | min:0 | Precio del producto |
| `cantidad` | integer | min:0 | Cantidad disponible |
| `id_unidad` | integer | debe existir en unidad | ID de la unidad de medida |
| `tipo_moneda` | string | PEN o USD | Tipo de moneda |
| `recibe_ofertas` | boolean | true/false | Si el producto acepta ofertas |
| `destacado` | boolean | true/false | Si el producto está destacado |
| `imagen_stock` | string | opcional | Imagen específica para el stock (base64, URL o ruta) |

**Nota:** Todos los campos son opcionales. Solo envía los campos que quieras actualizar. Puedes editar solo el producto, solo el stock, o ambos.

---

## 💻 Ejemplos

### Ejemplo 1: Actualizar solo el nombre

**cURL:**
```bash
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Nuevo nombre del producto"
  }'
```

**JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/productos/1', {
  method: 'PUT',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    nombre: 'Nuevo nombre del producto'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

### Ejemplo 2: Actualizar nombre y descripción

**cURL:**
```bash
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Maíz Amarillo Premium",
    "descripcion": "Maíz amarillo de alta calidad, ideal para consumo humano y animal"
  }'
```

**JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/productos/1', {
  method: 'PUT',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    nombre: 'Maíz Amarillo Premium',
    descripcion: 'Maíz amarillo de alta calidad, ideal para consumo humano y animal'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

### Ejemplo 3: Actualizar con imagen (base64)

**cURL:**
```bash
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Producto Actualizado",
    "imagen": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQ..."
  }'
```

**JavaScript/Fetch:**
```javascript
// Convertir imagen a base64
const fileInput = document.querySelector('input[type="file"]');
const file = fileInput.files[0];
const reader = new FileReader();

reader.onloadend = function() {
  const base64String = reader.result;
  
  fetch('http://localhost:8080/api/productos/1', {
    method: 'PUT',
    headers: {
      'Authorization': 'Bearer TU_TOKEN_JWT',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre: 'Producto Actualizado',
      imagen: base64String
    })
  })
  .then(response => response.json())
  .then(data => console.log(data));
};

reader.readAsDataURL(file);
```

---

### Ejemplo 4: Actualizar producto y stock completo

**cURL:**
```bash
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Maíz Amarillo Premium",
    "descripcion": "Maíz amarillo de alta calidad, ideal para consumo",
    "imagen": "https://example.com/nueva-imagen.jpg",
    "id_subcategoria": 20,
    "estado": "ACTIVO",
    "precio": 25.50,
    "cantidad": 100,
    "id_unidad": 1,
    "tipo_moneda": "PEN",
    "recibe_ofertas": true,
    "destacado": true,
    "imagen_stock": "data:image/jpeg;base64,..."
  }'
```

**JavaScript/Fetch:**
```javascript
fetch('http://localhost:8080/api/productos/1', {
  method: 'PUT',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_JWT',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    nombre: 'Maíz Amarillo Premium',
    descripcion: 'Maíz amarillo de alta calidad, ideal para consumo',
    imagen: 'https://example.com/nueva-imagen.jpg',
    id_subcategoria: 20,
    estado: 'ACTIVO',
    precio: 25.50,
    cantidad: 100,
    id_unidad: 1,
    tipo_moneda: 'PEN',
    recibe_ofertas: true,
    destacado: true,
    imagen_stock: 'data:image/jpeg;base64,...'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

### Ejemplo 5: Actualizar solo stock (precio y cantidad)

**cURL:**
```bash
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "precio": 30.00,
    "cantidad": 150
  }'
```

---

### Ejemplo 6: Actualizar solo opciones del stock

**cURL:**
```bash
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT" \
  -H "Content-Type: application/json" \
  -d '{
    "recibe_ofertas": true,
    "destacado": false,
    "estado": "ACTIVO"
  }'
```

---

## ✅ Respuesta Exitosa (200)

```json
{
  "status": true,
  "code": 200,
  "data": {
    "id_producto": 1,
    "nombre": "Maíz Amarillo Premium",
    "descripcion": "Maíz amarillo de alta calidad, ideal para consumo",
    "imagen": "https://example.com/imagen.jpg",
    "id_subcategoria": 20,
    "id_usuario": 2,
    "estado": "ACTIVO",
    "stock": {
      "id_stock": 5,
      "precio": 25.50,
      "cantidad": 100,
      "id_unidad": 1,
      "tipo_moneda": "PEN",
      "recibe_ofertas": true,
      "destacado": true,
      "imagen": "https://example.com/stock-imagen.jpg",
      "unidad": {
        "id_unidad": 1,
        "nombre": "Kilogramo (kg)"
      }
    }
  },
  "title": "Producto actualizado",
  "message": "Producto actualizado exitosamente"
}
```

---

## ❌ Errores Posibles

### 404 - Producto no encontrado

```json
{
  "status": false,
  "code": 404,
  "message": "Producto no encontrado",
  "title": "No encontrado"
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
    "nombre": [
      "El campo nombre no puede tener más de 300 caracteres."
    ],
    "id_subcategoria": [
      "El campo id_subcategoria seleccionado no es válido."
    ]
  },
  "title": "Error de validación"
}
```

---

## 📝 Notas Importantes

1. **Todos los campos son opcionales**: Solo envía los campos que quieras actualizar
2. **Puedes editar producto y stock por separado o juntos**
3. **Imagen del producto**: Puede ser:
   - Base64: `data:image/jpeg;base64,/9j/4AAQSkZJRg...`
   - URL: `https://example.com/imagen.jpg`
   - Ruta: `/storage/productos/imagen.jpg`
4. **Imagen del stock**: Similar al producto, pero se guarda en carpeta `stocks`
5. **El sistema procesa automáticamente** las imágenes base64 y las guarda
6. **Si no existe stock**, se crea automáticamente cuando envías datos de stock
7. **Solo puedes editar productos que te pertenecen** (verificado por token JWT)
8. **Estado del producto**: Solo puede ser `ACTIVO` o `INACTIVO`
9. **Tipo de moneda**: Solo puede ser `PEN` o `USD`

---

## 🔄 Ejemplo Completo de Flujo

```bash
# 1. Obtener token
TOKEN=$(curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"usuario":"tu_usuario","clave":"tu_clave"}' \
  | jq -r '.data.token')

# 2. Ver producto actual
curl -X GET http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer $TOKEN"

# 3. Actualizar producto
curl -X PUT http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Nuevo Nombre",
    "descripcion": "Nueva descripción"
  }'

# 4. Verificar cambios
curl -X GET http://localhost:8080/api/productos/1 \
  -H "Authorization: Bearer $TOKEN"
```

---

## 🎯 Resumen

- **Endpoint**: `PUT /api/productos/{id}`
- **Autenticación**: Requerida (JWT)
- **Campos del producto**: `nombre`, `descripcion`, `imagen`, `id_subcategoria`, `estado`
- **Campos del stock**: `precio`, `cantidad`, `id_unidad`, `tipo_moneda`, `recibe_ofertas`, `destacado`, `imagen_stock`
- **Solo actualiza los campos que envíes**
- **Puedes editar producto y stock juntos o por separado**
- **Solo puedes editar tus propios productos**

