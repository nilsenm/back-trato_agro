# API de Mis Ventas y Cambiar Estado

## 1. Obtener Mis Ventas (como vendedor)

Obtiene todas las ventas donde el usuario autenticado es el vendedor (tiene productos vendidos).

### Endpoint
```
GET /api/ventas/mis-ventas
```

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Respuesta Exitosa (200)
```json
{
  "success": true,
  "message": "Ventas obtenidas exitosamente",
  "title": "Mis ventas",
  "data": {
    "vendedor": {
      "id_usuario": 1,
      "nombre": "Juan Pérez",
      "username": "juanperez",
      "correo": "juan@example.com",
      "documento": "12345678",
      "tipo_persona": "N",
      "persona": {
        "tipo": "NATURAL",
        "dni": "12345678",
        "nombres": "Juan",
        "apellidos": "Pérez",
        "nombre_completo": "Juan Pérez",
        "documento": "12345678",
        "tipo_documento": "DNI"
      }
    },
    "ventas": [
      {
        "id_venta": 1,
        "fecha": "2025-12-06",
        "hora": "10:30:00",
        "id_usuario_compra": 2,
        "id_distrito": 1,
        "direccion": "Av. Principal 123",
        "tipo_pago": "CONTRA_ENTREGA",
        "estado": "PEDIDO",
        "ubicacion": {
          "id_distrito": 1,
          "distrito": "Lima",
          "id_provincia": 1,
          "provincia": "Lima",
          "id_departamento": 1,
          "departamento": "Lima",
          "direccion_completa": "Av. Principal 123, Lima, Lima, Lima"
        },
        "comprador": {
          "id_usuario": 2,
          "nombre": "María García",
          "username": "mariagarcia",
          "correo": "maria@example.com",
          "documento": "87654321",
          "tipo_persona": "N",
          "persona": {
            "tipo": "NATURAL",
            "dni": "87654321",
            "nombres": "María",
            "apellidos": "García",
            "documento": "87654321",
            "tipo_documento": "DNI"
          }
        },
        "items": [
          {
            "id_detalle_venta": 1,
            "id_stock": 1,
            "cantidad": 2,
            "precio_unitario": 50.00,
            "subtotal": 100.00,
            "tipo_moneda": "PEN",
            "producto": {
              "id_producto": 1,
              "nombre": "Maíz",
              "descripcion": "Maíz de calidad",
              "imagen": "maiz.jpg"
            },
            "comprador": {
              "id_usuario": 2,
              "nombre": "María García",
              "username": "mariagarcia",
              "correo": "maria@example.com",
              "documento": "87654321",
              "tipo_persona": "N",
              "persona": {
                "tipo": "NATURAL",
                "dni": "87654321",
                "nombres": "María",
                "apellidos": "García",
                "documento": "87654321",
                "tipo_documento": "DNI"
              }
            },
            "unidad": {
              "id_unidad": 1,
              "nombre": "Kilogramo"
            }
          }
        ],
        "resumen": {
          "subtotal": 100.00,
          "total": 100.00,
          "tipo_moneda": "PEN",
          "cantidad_items": 1
        }
      }
    ],
    "total_ventas": 1
  }
}
```

### Respuesta sin ventas (200)
```json
{
  "success": true,
  "message": "No se encontraron ventas",
  "title": "Mis ventas",
  "data": {
    "vendedor": null,
    "ventas": [],
    "total_ventas": 0
  }
}
```

### Errores
- **401 Unauthorized**: Token inválido o expirado
- **500 Internal Server Error**: Error del servidor

---

## 2. Cambiar Estado de Venta

Permite cambiar el estado de una venta. Solo el comprador o el vendedor pueden cambiar el estado.

### Endpoint
```
PUT /api/ventas/{id}/estado
```

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Parámetros de URL
- `id` (integer, requerido): ID de la venta

### Body (JSON)
```json
{
  "estado": "ENVIADO"
}
```

### Estados Válidos
- `PEDIDO`: Pedido realizado
- `ENVIADO`: Producto enviado
- `ENTREGADO`: Producto entregado
- `CANCELADO`: Venta cancelada

### Respuesta Exitosa (200)
```json
{
  "success": true,
  "message": "Estado de venta actualizado exitosamente",
  "title": "Estado actualizado",
  "data": {
    "id_venta": 1,
    "estado": "ENVIADO"
  }
}
```

### Errores
- **400 Bad Request**: Estado inválido o faltante
  ```json
  {
    "success": false,
    "message": "The estado field is required.",
    "title": "Error de validación",
    "errors": {
      "estado": ["The estado field is required."]
    }
  }
  ```
- **401 Unauthorized**: Token inválido o expirado
- **403 Forbidden**: El usuario no tiene permiso para modificar esta venta
  ```json
  {
    "success": false,
    "message": "No tienes permiso para modificar esta venta",
    "codeError": "403",
    "title": "Error de permisos"
  }
  ```
- **404 Not Found**: Venta no encontrada
  ```json
  {
    "success": false,
    "message": "Venta no encontrada",
    "title": "No encontrado"
  }
  ```
- **500 Internal Server Error**: Error del servidor

### Ejemplo de Uso

#### Cambiar estado a "ENVIADO"
```bash
curl -X PUT http://localhost:8080/api/ventas/1/estado \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "estado": "ENVIADO"
  }'
```

#### Cambiar estado a "ENTREGADO"
```bash
curl -X PUT http://localhost:8080/api/ventas/1/estado \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "estado": "ENTREGADO"
  }'
```

#### Cambiar estado a "CANCELADO"
```bash
curl -X PUT http://localhost:8080/api/ventas/1/estado \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "estado": "CANCELADO"
  }'
```

---

## Notas Importantes

1. **Autenticación**: Ambas APIs requieren un token JWT válido en el header `Authorization`.

2. **Permisos**: 
   - `mis-ventas`: Solo muestra las ventas donde el usuario autenticado es el vendedor.
   - `cambiar-estado`: Solo el comprador o el vendedor de la venta pueden cambiar el estado.

3. **Estados de Venta**:
   - `PEDIDO`: Estado inicial cuando se crea la venta.
   - `ENVIADO`: El vendedor ha enviado el producto.
   - `ENTREGADO`: El producto ha sido entregado al comprador.
   - `CANCELADO`: La venta ha sido cancelada.

4. **Filtrado de Items**: En `mis-ventas`, solo se muestran los items de productos que pertenecen al vendedor autenticado. Si una venta tiene productos de múltiples vendedores, solo se mostrarán los items del vendedor actual.

5. **Ordenamiento**: Las ventas se ordenan por `id_venta` descendente (más recientes primero).

