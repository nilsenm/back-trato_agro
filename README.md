# TratoAgro

Sistema de gestión agrícola construido con Laravel 12, Docker, PostgreSQL y Arquitectura Limpia.

## 📋 Tabla de Contenidos

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Acceso](#acceso)
- [Base de Datos](#base-de-datos)
- [Arquitectura](#arquitectura)
- [API Endpoints](#api-endpoints)
- [Ejemplos de Uso de la API](#ejemplos-de-uso-de-la-api)
- [Comandos Útiles](#comandos-útiles)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Migración Completa](#migración-completa)

## Requisitos

- Docker (versión 20.10 o superior)
- Docker Compose (versión 2.0 o superior)
- Git

## Instalación

### Opción 1: Usando Make (Recomendado)

```bash
make install
```

### Opción 2: Instalación Manual

1. **Copiar el archivo de entorno:**
   ```bash
   cp .env.example .env
   ```

2. **Construir y levantar los contenedores:**
   ```bash
   docker compose up -d --build
   ```

3. **Instalar dependencias de Composer:**
   ```bash
   docker compose exec app composer install
   ```

4. **Generar la clave de la aplicación:**
   ```bash
   docker compose exec app php artisan key:generate
   ```

5. **Ejecutar las migraciones:**
   ```bash
   docker compose exec app php artisan migrate
   ```

6. **Ejecutar seeders:**
   ```bash
   docker compose exec app php artisan db:seed
   ```

7. **Generar clave JWT:**
   ```bash
   docker compose exec app php artisan jwt:secret
   ```

## Acceso

- **Aplicación**: http://localhost:8080
- **pgAdmin**: http://localhost:8081
  - Email: `admin@tratoagro.com`
  - Password: `admin`

### Configurar pgAdmin

Cuando accedas a pgAdmin por primera vez, necesitas agregar el servidor PostgreSQL:

1. Click derecho en **"Servers"** → **Register** → **Server**
2. **Pestaña "General":**
   - Name: `TratoAgro DB`
3. **Pestaña "Connection":**
   - Host name/address: `db`
   - Port: `5432`
   - Maintenance database: `tratoagro`
   - Username: `tratoagro`
   - Password: `password`
   - ✅ Marcar "Save password"
4. Click en **"Save"**

## Base de Datos

### Configuración PostgreSQL

- **Host**: `db` (dentro de Docker) o `localhost:5432` (desde el host)
- **Database**: `tratoagro`
- **Username**: `tratoagro`
- **Password**: `password`

### Estructura de la Base de Datos

La base de datos incluye las siguientes tablas:

- `categoria` - Categorías de productos
- `subcategoria` - Subcategorías
- `producto` - Productos
- `stock` - Inventario
- `usuario` - Usuarios
- `persona_natural` - Personas naturales
- `persona_juridica` - Personas jurídicas
- `venta` - Ventas
- `detalle_venta` - Detalles de venta
- `departamento`, `provincia`, `distrito` - Ubicaciones geográficas
- `unidad` - Unidades de medida

## Arquitectura

Este proyecto utiliza **Arquitectura Limpia (Clean Architecture)** con las siguientes capas:

### Domain (Dominio)
**Ubicación:** `app/Domain/`

Contiene la lógica de negocio pura, sin dependencias externas.

- **Entities**: Entidades del dominio (Categoria, Producto, Usuario, Stock, Venta, etc.)
- **Interfaces**: Contratos y repositorios (RepositoryInterface, CategoriaRepositoryInterface, etc.)

### Application (Aplicación)
**Ubicación:** `app/Application/`

Contiene los casos de uso y la lógica de aplicación.

- **Services**: Servicios de aplicación (CategoriaService, ProductoService, etc.)
- **DTOs**: Objetos de transferencia de datos

### Infrastructure (Infraestructura)
**Ubicación:** `app/Infrastructure/`

Implementa los detalles técnicos y acceso a datos.

- **Repositories**: Implementaciones de repositorios con Eloquent
- **Models**: Modelos de Eloquent

### Presentation (Presentación)
**Ubicación:** `app/Presentation/`

Capa de interfaz con el usuario (HTTP).

- **Controllers**: Controladores HTTP
- **Routes**: Definición de rutas API

### Flujo de Datos

```
Request → Controller → Service → Repository → Model → Database
         ↓            ↓         ↓
      Response    DTO      Entity
```

## API Endpoints

### Categorías
```
GET    /api/categorias
GET    /api/categorias/{id}
POST   /api/categorias
PUT    /api/categorias/{id}
DELETE /api/categorias/{id}
```

### Productos
```
GET    /api/productos
GET    /api/productos/{id}
POST   /api/productos
PUT    /api/productos/{id}
DELETE /api/productos/{id}
GET    /api/productos/subcategoria/{idSubcategoria}
```

### Stocks
```
GET    /api/stocks
GET    /api/stocks/{id}
POST   /api/stocks
PUT    /api/stocks/{id}
DELETE /api/stocks/{id}
GET    /api/stocks/usuario/{idUsuario}
GET    /api/stocks/producto/{idProducto}
GET    /api/stocks/disponibles
```

### Unidades
```
GET    /api/unidades
GET    /api/unidades/{id}
```

### Subcategorías
```
GET    /api/subcategorias
GET    /api/subcategorias/{id}
POST   /api/subcategorias
GET    /api/subcategorias/categoria/{idCategoria}
```

### Ubicaciones
```
GET    /api/ubicaciones/departamentos
GET    /api/ubicaciones/provincias/{idDepartamento}
GET    /api/ubicaciones/distritos/{idProvincia}
```

### Usuarios
```
GET    /api/usuarios
GET    /api/usuarios/{id}
POST   /api/usuarios
PUT    /api/usuarios/{id}
DELETE /api/usuarios/{id}
POST   /api/usuarios/login
```

### Personas Naturales
```
POST   /api/personas-naturales/registro          # Registro completo (público): crea usuario + persona natural
POST   /api/personas-naturales                   # Registro simple (público): solo persona natural
GET    /api/personas-naturales                   # Listar (requiere JWT)
GET    /api/personas-naturales/{dni}             # Obtener por DNI (requiere JWT)
PUT    /api/personas-naturales/{dni}             # Actualizar (requiere JWT)
DELETE /api/personas-naturales/{dni}             # Eliminar (requiere JWT)
POST   /api/personas-naturales/{dni}/enlazar-usuario  # Enlazar usuario existente (requiere JWT)
```

### Personas Jurídicas
```
GET    /api/personas-juridicas
GET    /api/personas-juridicas/{ruc}
POST   /api/personas-juridicas
PUT    /api/personas-juridicas/{ruc}
DELETE /api/personas-juridicas/{ruc}
POST   /api/personas-juridicas/{ruc}/enlazar-usuario
```

### Ventas
```
GET    /api/ventas
GET    /api/ventas/{id}
POST   /api/ventas
PUT    /api/ventas/{id}
DELETE /api/ventas/{id}
GET    /api/ventas/usuario/{idUsuario}
GET    /api/ventas/usuario/{idUsuario}/ultima
```

### Detalles de Venta
```
GET    /api/detalles-venta
GET    /api/detalles-venta/{id}
POST   /api/detalles-venta
PUT    /api/detalles-venta/{id}
DELETE /api/detalles-venta/{id}
GET    /api/detalles-venta/venta/{idVenta}
```

### Consultas Externas
```
POST   /api/consultas/dni
POST   /api/consultas/ruc
```

### Reportes
```
GET    /api/reportes/ventas/categoria/{idCategoria}?fecha_desde=YYYY-MM-DD
```

## Formato de Respuesta

Todas las APIs devuelven respuestas en el formato estándar `ApplicationResponse` compatible con Dart/Flutter:

```json
{
  "status": true,              // bool - Estado de la operación
  "code": 200,                 // int - Código HTTP
  "data": [],                  // List - Datos de respuesta
  "otherData": [],             // List - Datos adicionales
  "filter": [],                // List - Filtros aplicados
  "title": "Éxito",           // String - Título de la respuesta
  "message": "Operación exitosa", // String - Mensaje descriptivo
  "codeError": "",             // String - Código de error (vacío si éxito)
  "messageError": "",          // String - Mensaje de error (vacío si éxito)
  "line": 0,                  // int - Línea de error (0 si éxito)
  "file": ""                   // String - Archivo de error (vacío si éxito)
}
```

## Autenticación JWT

El proyecto utiliza **JWT (JSON Web Tokens)** para autenticación.

### Endpoints de Autenticación

```
POST   /api/auth/login      - Iniciar sesión (obtener token)
POST   /api/auth/me         - Obtener usuario autenticado (requiere token)
POST   /api/auth/logout     - Cerrar sesión (requiere token)
POST   /api/auth/refresh    - Refrescar token
```

### Uso del Token

1. **Login:**
   ```bash
   POST /api/auth/login
   {
     "correo": "usuario@example.com",
     "clave": "password123"
   }
   ```

2. **Respuesta:**
   ```json
   {
     "success": true,
     "message": "Login exitoso",
     "data": {
       "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
       "token_type": "bearer",
       "expires_in": 3600,
       "usuario": { ... }
     }
   }
   ```

3. **Usar el token en peticiones:**
   ```bash
   Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
   ```

## Ejemplos de Uso de la API

Para ver ejemplos detallados de cómo usar la API, incluyendo código en JavaScript, Dart/Flutter y curl, consulta el archivo **[API_EXAMPLES.md](API_EXAMPLES.md)**.

## Registro de Personas Naturales

Para registrarse como persona natural, puedes usar el endpoint de registro completo que crea tanto el usuario como la persona natural:

### Registro Completo (Recomendado)

```bash
POST /api/personas-naturales/registro
Content-Type: application/json

{
  "dni": "12345678",
  "nombres": "Juan",
  "apellidos": "Pérez",
  "direccion": "Av. Principal 123",
  "celular": "987654321",
  "pais": "Perú",
  "departamento": 1,
  "provincia": 1,
  "distrito": 1,
  "correo": "juan.perez@example.com",
  "clave": "password123",
  "nombre": "Juan Pérez",
  "tipo_vendedor": "NAT"
}
```

**Respuesta exitosa:**
```json
{
  "status": true,
  "code": 201,
  "data": {
    "persona_natural": {
      "dni": "12345678",
      "nombres": "Juan",
      "apellidos": "Pérez",
      ...
    },
    "usuario": {
      "id": 1,
      "documento": "12345678",
      "correo": "juan.perez@example.com",
      ...
    }
  },
  "message": "Registro completado exitosamente",
  "title": "Registro exitoso"
}
```

### Registro Simple (Solo Persona Natural)

Si solo necesitas crear la persona natural sin usuario:

```bash
POST /api/personas-naturales
Content-Type: application/json

{
  "dni": "12345678",
  "nombres": "Juan",
  "apellidos": "Pérez",
  "direccion": "Av. Principal 123",
  "celular": "987654321"
}
```

**Nota:** Después del registro, puedes iniciar sesión con el correo y contraseña proporcionados usando `/api/auth/login`.

### Configuración JWT

Después de instalar dependencias, generar la clave JWT:

```bash
docker compose exec app php artisan jwt:secret
```

Esto generará `JWT_SECRET` en tu archivo `.env`.

### Rutas Protegidas

Todas las rutas (excepto `/api/auth/login`, `/api/auth/refresh`, `/api/health`, `/api/unidades`, `/api/ubicaciones/*`) requieren autenticación JWT.

## Comandos Útiles

### Gestión de Contenedores

```bash
# Levantar contenedores
docker compose up -d

# Detener contenedores
docker compose down

# Reiniciar contenedores
docker compose restart

# Ver logs
docker compose logs -f
```

### Comandos de Laravel

```bash
# Acceder al shell del contenedor
docker compose exec app bash

# Ejecutar migraciones
docker compose exec app php artisan migrate

# Ejecutar seeders
docker compose exec app php artisan db:seed

# Limpiar caché
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Ejecutar tests
docker compose exec app php artisan test
```

## Estructura del Proyecto

```
app/
├── Domain/
│   ├── Entities/          # 9 entidades del dominio
│   └── Interfaces/        # Interfaces de repositorios
├── Application/
│   └── Services/          # 12 servicios de aplicación
├── Infrastructure/
│   ├── Models/           # Modelos Eloquent
│   └── Repositories/     # 9 repositorios implementados
└── Presentation/
    ├── Http/
    │   └── Controllers/  # 11 controladores RESTful
    └── Routes/
        └── api.php       # Rutas API
```

## Migración Completa

### ✅ Estado de Migración

**MIGRACIÓN 100% COMPLETA** - Todas las funcionalidades del sistema antiguo están migradas.

#### Endpoints Migrados

1. ✅ **Categorías** - CRUD completo
2. ✅ **Productos** - CRUD completo + filtros
3. ✅ **Stocks** - CRUD completo + filtros
4. ✅ **Unidades** - Listado y detalle
5. ✅ **Subcategorías** - CRUD completo + por categoría
6. ✅ **Ubicaciones** - Departamentos, Provincias, Distritos
7. ✅ **Usuarios** - CRUD completo + autenticación
8. ✅ **Ventas** - CRUD completo + por usuario
9. ✅ **Detalles de Venta** - CRUD completo
10. ✅ **Personas Naturales** - CRUD completo + enlazar usuario
11. ✅ **Personas Jurídicas** - CRUD completo + enlazar usuario
12. ✅ **Reportes** - Ventas por categoría
13. ✅ **Consultas Externas** - DNI/RUC

#### Archivos Migrados

- ✅ **Imágenes de categorías** → `public/img/` (6 imágenes)
- ✅ **Imágenes de fondo** → `public/fondos/` (7 imágenes)
- ✅ **Base de datos** → PostgreSQL con migraciones Laravel

### 🗑️ Eliminar Carpeta Antigua

La carpeta `tratoagro/` puede eliminarse completamente porque:

✅ Todos los endpoints están migrados  
✅ Todas las imágenes están copiadas  
✅ La base de datos está migrada  
✅ Todas las funcionalidades están implementadas  

**Comando para eliminar:**

```bash
# Verificar que todo esté migrado
ls -la public/img/
ls -la public/fondos/

# Eliminar carpeta antigua
rm -rf tratoagro/
```

## Estructura de Servicios Docker

- **app**: Contenedor PHP 8.3 con Laravel
- **nginx**: Servidor web Nginx
- **db**: Base de datos PostgreSQL 16
- **redis**: Cache y sesiones
- **pgadmin**: Interfaz web para PostgreSQL

## Solución de Problemas

### Error: Puerto ya en uso

Si los puertos 8080, 8081, 5432 o 6379 están en uso, puedes cambiarlos en `docker-compose.yml`.

### Error: Permisos

```bash
sudo chown -R $USER:$USER .
```

### Reconstruir desde cero

```bash
docker compose down -v
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

## Características

- ✅ Arquitectura Limpia implementada
- ✅ Separación de responsabilidades
- ✅ Inyección de dependencias
- ✅ Repositorios con interfaces
- ✅ Servicios de aplicación
- ✅ Controladores RESTful
- ✅ Validación de peticiones
- ✅ Respuestas JSON estandarizadas
- ✅ Migraciones para PostgreSQL
- ✅ Seeders configurados
- ✅ Docker Compose completo
- ✅ Consultas externas (DNI/RUC)
- ✅ Sistema de reportes
# back-trato_agro
