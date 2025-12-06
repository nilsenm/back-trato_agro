# Ejemplos de Uso de la API - TratoAgro

## Base URL
```
http://localhost/api
```

---

## 1. Registro de Persona Natural (Recomendado)

### Endpoint: `POST /api/personas-naturales/registro`

Este endpoint crea tanto el usuario como la persona natural en un solo paso.

**Request:**
```bash
curl -X POST http://localhost/api/personas-naturales/registro \
  -H "Content-Type: application/json" \
  -d '{
    "dni": "12345678",
    "nombres": "Juan",
    "apellidos": "Pérez García",
    "direccion": "Av. Principal 123, Lima",
    "celular": "987654321",
    "pais": "Perú",
    "departamento": 1,
    "provincia": 1,
    "distrito": 1,
    "correo": "juan.perez@example.com",
    "clave": "password123",
    "nombre": "Juan Pérez García",
    "tipo_vendedor": "NAT"
  }'
```

**Response (201 Created):**
```json
{
  "status": true,
  "code": 201,
  "data": {
    "persona_natural": {
      "dni": "12345678",
      "nombres": "Juan",
      "apellidos": "Pérez García",
      "direccion": "Av. Principal 123, Lima",
      "celular": "987654321",
      "pais": "Perú",
      "departamento": 1,
      "provincia": 1,
      "distrito": 1,
      "id_usuario": 1
    },
    "usuario": {
      "id": 1,
      "documento": "12345678",
      "nombre": "Juan Pérez García",
      "correo": "juan.perez@example.com",
      "estado": "D",
      "tipo_vendedor": "NAT",
      "tipo_persona": "N"
    }
  },
  "otherData": [],
  "filter": [],
  "title": "Registro exitoso",
  "message": "Registro completado exitosamente",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

**Campos requeridos:**
- `dni` (string, 8 caracteres): DNI de la persona
- `correo` (email): Correo electrónico (debe ser único)
- `clave` (string, min 6, max 16): Contraseña

**Campos opcionales:**
- `nombres`, `apellidos`, `direccion`, `celular`, `pais`
- `departamento`, `provincia`, `distrito` (IDs numéricos)
- `nombre`: Nombre completo del usuario (si no se proporciona, se usa nombres + apellidos)
- `tipo_vendedor`: Por defecto "NAT"

---

## 2. Registro Simple (Solo Persona Natural)

### Endpoint: `POST /api/personas-naturales`

Crea solo la persona natural sin usuario. Útil si quieres crear el usuario después.

**Request:**
```bash
curl -X POST http://localhost/api/personas-naturales \
  -H "Content-Type: application/json" \
  -d '{
    "dni": "87654321",
    "nombres": "María",
    "apellidos": "González",
    "direccion": "Jr. Los Olivos 456",
    "celular": "912345678"
  }'
```

**Response (201 Created):**
```json
{
  "status": true,
  "code": 201,
  "data": {
    "dni": "87654321",
    "nombres": "María",
    "apellidos": "González",
    "direccion": "Jr. Los Olivos 456",
    "celular": "912345678",
    "pais": null,
    "departamento": null,
    "provincia": null,
    "distrito": null,
    "id_usuario": null
  },
  "otherData": [],
  "filter": [],
  "title": "Operación exitosa",
  "message": "Persona natural creada exitosamente",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

---

## 3. Login (Después del Registro)

### Endpoint: `POST /api/auth/login`

Una vez registrado, puedes iniciar sesión con el correo y contraseña.

**Request:**
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "correo": "juan.perez@example.com",
    "clave": "password123"
  }'
```

**Response (200 OK):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "success": true,
    "message": "Login exitoso",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600,
    "usuario": {
      "id": 1,
      "documento": "12345678",
      "nombre": "Juan Pérez García",
      "correo": "juan.perez@example.com",
      "tipo_vendedor": "NAT",
      "tipo_persona": "N"
    }
  },
  "otherData": [],
  "filter": [],
  "title": "Autenticación exitosa",
  "message": "Login exitoso",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

**Guarda el token** para usarlo en las siguientes peticiones.

---

## 4. Obtener Información del Usuario Autenticado

### Endpoint: `POST /api/auth/me`

Obtiene la información del usuario autenticado usando el token JWT.

**Request:**
```bash
curl -X POST http://localhost/api/auth/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

**Response (200 OK):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "id": 1,
    "documento": "12345678",
    "nombre": "Juan Pérez García",
    "correo": "juan.perez@example.com",
    "tipo_vendedor": "NAT",
    "tipo_persona": "N"
  },
  "otherData": [],
  "filter": [],
  "title": "Operación exitosa",
  "message": "Usuario obtenido exitosamente",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

---

## 5. Obtener Persona Natural por DNI

### Endpoint: `GET /api/personas-naturales/{dni}`

Obtiene la información de una persona natural por su DNI (requiere autenticación).

**Request:**
```bash
curl -X GET http://localhost/api/personas-naturales/12345678 \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

**Response (200 OK):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "dni": "12345678",
    "nombres": "Juan",
    "apellidos": "Pérez García",
    "direccion": "Av. Principal 123, Lima",
    "celular": "987654321",
    "pais": "Perú",
    "departamento": 1,
    "provincia": 1,
    "distrito": 1,
    "id_usuario": 1
  },
  "otherData": [],
  "filter": [],
  "title": "Operación exitosa",
  "message": "Persona natural obtenida exitosamente",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

---

## 6. Actualizar Persona Natural

### Endpoint: `PUT /api/personas-naturales/{dni}`

Actualiza la información de una persona natural (requiere autenticación).

**Request:**
```bash
curl -X PUT http://localhost/api/personas-naturales/12345678 \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "nombres": "Juan Carlos",
    "celular": "999888777",
    "direccion": "Av. Nueva Dirección 789"
  }'
```

**Response (200 OK):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "dni": "12345678",
    "nombres": "Juan Carlos",
    "apellidos": "Pérez García",
    "direccion": "Av. Nueva Dirección 789",
    "celular": "999888777",
    ...
  },
  "otherData": [],
  "filter": [],
  "title": "Operación exitosa",
  "message": "Persona natural actualizada exitosamente",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

---

## 7. Enlazar Usuario Existente a Persona Natural

### Endpoint: `POST /api/personas-naturales/{dni}/enlazar-usuario`

Si ya tienes una persona natural y un usuario creados por separado, puedes enlazarlos.

**Request:**
```bash
curl -X POST http://localhost/api/personas-naturales/87654321/enlazar-usuario \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "id_usuario": 2
  }'
```

**Response (200 OK):**
```json
{
  "status": true,
  "code": 200,
  "data": {
    "dni": "87654321",
    "nombres": "María",
    "apellidos": "González",
    "id_usuario": 2,
    ...
  },
  "otherData": [],
  "filter": [],
  "title": "Operación exitosa",
  "message": "Usuario enlazado exitosamente",
  "codeError": "",
  "messageError": "",
  "line": 0,
  "file": ""
}
```

---

## 8. Obtener Ubicaciones (Público)

Antes de registrar, puedes obtener las ubicaciones disponibles:

### Departamentos
```bash
curl -X GET http://localhost/api/ubicaciones/departamentos
```

### Provincias
```bash
curl -X GET http://localhost/api/ubicaciones/provincias/1
```

### Distritos
```bash
curl -X GET http://localhost/api/ubicaciones/distritos/1
```

---

## Flujo Completo de Registro

1. **Obtener ubicaciones** (opcional):
   ```bash
   GET /api/ubicaciones/departamentos
   GET /api/ubicaciones/provincias/{idDepartamento}
   GET /api/ubicaciones/distritos/{idProvincia}
   ```

2. **Registrarse como persona natural**:
   ```bash
   POST /api/personas-naturales/registro
   ```

3. **Iniciar sesión**:
   ```bash
   POST /api/auth/login
   ```

4. **Usar el token** en todas las peticiones protegidas:
   ```bash
   Authorization: Bearer {token}
   ```

---

## Errores Comunes

### Error 409 - Persona ya tiene usuario
```json
{
  "status": false,
  "code": 409,
  "data": [],
  "title": "Conflicto",
  "message": "Esta persona natural ya tiene un usuario asociado",
  "codeError": "409",
  "messageError": "Esta persona natural ya tiene un usuario asociado"
}
```

### Error 422 - Validación fallida
```json
{
  "status": false,
  "code": 422,
  "data": {
    "dni": ["El campo dni es obligatorio."],
    "correo": ["El correo ya está en uso."]
  },
  "title": "Error de validación",
  "message": "Validación fallida",
  "codeError": "422",
  "messageError": "{\"dni\":[\"El campo dni es obligatorio.\"]}"
}
```

### Error 401 - No autenticado
```json
{
  "status": false,
  "code": 401,
  "data": [],
  "title": "Error",
  "message": "Token no proporcionado",
  "codeError": "401",
  "messageError": "Token no proporcionado"
}
```

---

## Ejemplo con JavaScript/Fetch

```javascript
// 1. Registro
const registro = async () => {
  const response = await fetch('http://localhost/api/personas-naturales/registro', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      dni: '12345678',
      nombres: 'Juan',
      apellidos: 'Pérez',
      correo: 'juan@example.com',
      clave: 'password123',
      celular: '987654321'
    })
  });
  
  const data = await response.json();
  console.log(data);
};

// 2. Login
const login = async () => {
  const response = await fetch('http://localhost/api/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      correo: 'juan@example.com',
      clave: 'password123'
    })
  });
  
  const data = await response.json();
  const token = data.data.token;
  localStorage.setItem('token', token);
  return token;
};

// 3. Petición autenticada
const obtenerPersona = async (dni) => {
  const token = localStorage.getItem('token');
  const response = await fetch(`http://localhost/api/personas-naturales/${dni}`, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  
  const data = await response.json();
  console.log(data);
};
```

---

## Ejemplo con Dart/Flutter

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

// 1. Registro
Future<ApplicationResponse> registrarPersonaNatural({
  required String dni,
  required String correo,
  required String clave,
  String? nombres,
  String? apellidos,
}) async {
  final response = await http.post(
    Uri.parse('http://localhost/api/personas-naturales/registro'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'dni': dni,
      'correo': correo,
      'clave': clave,
      'nombres': nombres,
      'apellidos': apellidos,
    }),
  );
  
  return ApplicationResponse.fromJson(jsonDecode(response.body));
}

// 2. Login
Future<String?> login(String correo, String clave) async {
  final response = await http.post(
    Uri.parse('http://localhost/api/auth/login'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'correo': correo,
      'clave': clave,
    }),
  );
  
  final data = ApplicationResponse.fromJson(jsonDecode(response.body));
  if (data.status) {
    return data.data['token'];
  }
  return null;
}

// 3. Petición autenticada
Future<ApplicationResponse> obtenerPersona(String dni, String token) async {
  final response = await http.get(
    Uri.parse('http://localhost/api/personas-naturales/$dni'),
    headers: {
      'Authorization': 'Bearer $token',
    },
  );
  
  return ApplicationResponse.fromJson(jsonDecode(response.body));
}
```








