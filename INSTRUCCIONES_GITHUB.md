# 📤 Instrucciones para Subir Código a GitHub

El código está listo y con commit realizado. Necesitas hacer el push manualmente desde tu terminal.

## ✅ Lo que ya está hecho:
- ✅ Repositorio git inicializado
- ✅ Todos los archivos agregados
- ✅ Commit inicial realizado (303 archivos)
- ✅ Branch cambiado a `main`
- ✅ Remote agregado

## 🚀 Opción 1: Usar HTTPS (Más Fácil)

Ya está configurado con HTTPS. Solo ejecuta:

```bash
cd /home/nilsen/sm/tratoagro
git push -u origin main
```

**Si te pide credenciales:**
- Username: `nilsenm`
- Password: Tu **Personal Access Token** (no tu contraseña de GitHub)

### Cómo crear un Personal Access Token:
1. Ve a GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Click en "Generate new token (classic)"
3. Dale un nombre como "back-trato-agro"
4. Selecciona el scope `repo` (full control of private repositories)
5. Click en "Generate token"
6. **Copia el token** (solo se muestra una vez)
7. Úsalo como password cuando git te lo pida

---

## 🔑 Opción 2: Configurar SSH (Recomendado para el futuro)

### 1. Generar clave SSH (si no tienes una):
```bash
ssh-keygen -t ed25519 -C "tu_email@ejemplo.com"
# Presiona Enter para usar ubicación por defecto
# Opcional: agrega una passphrase o presiona Enter
```

### 2. Ver tu clave pública:
```bash
cat ~/.ssh/id_ed25519.pub
```

### 3. Copiar la clave y agregarla a GitHub:
1. Ve a GitHub → Settings → SSH and GPG keys
2. Click en "New SSH key"
3. Pega la clave pública
4. Guarda

### 4. Cambiar el remote a SSH:
```bash
cd /home/nilsen/sm/tratoagro
git remote remove origin
git remote add origin git@github.com:nilsenm/back-trato_agro.git
git push -u origin main
```

---

## 📝 Comandos para Ejecutar Manualmente

Ejecuta estos comandos en tu terminal:

```bash
# Ir al directorio
cd /home/nilsen/sm/tratoagro

# Verificar estado
git status

# Ver remote configurado
git remote -v

# Hacer push (con HTTPS - te pedirá credenciales)
git push -u origin main
```

---

## ✅ Verificación

Después del push, verifica en:
- https://github.com/nilsenm/back-trato_agro

Deberías ver todos tus archivos allí.

---

## 🔄 Para Futuros Cambios

Después del primer push, puedes usar:

```bash
git add .
git commit -m "Tu mensaje de commit"
git push
```

---

¿Necesitas ayuda con alguna configuración? 🚀

