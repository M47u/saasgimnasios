# 📧 Configuración de Email - SaaS Gimnasios

## El problema
Los emails no se estaban enviando porque `MAIL_MAILER=log` solo guardaba en logs.

## La solución
He actualizado `.env` para usar **SMTP**. Ahora necesitas completar las credenciales del correo emisor del SaaS.

---

## 🟠 Opción recomendada: Hostinger Mail

Si el SaaS tiene un correo propio en Hostinger, usa esa casilla como emisor único para todos los gimnasios.

### Pasos:
1. En Hostinger, crea o identifica el correo del SaaS, por ejemplo `correo-del-saas@tudominio.com`
2. Abre la sección de configuración SMTP de ese buzón
3. Copia los datos de servidor, puerto, usuario y contraseña
4. Actualiza `.env` con esos valores

### Configuración base:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=correo-del-saas@tudominio.com
MAIL_PASSWORD=tu_contraseña_smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="correo-del-saas@tudominio.com"
MAIL_FROM_NAME="SaaS Gimnasios"
```

### Notas importantes:
✅ Un solo correo emisor para todo el SaaS  
✅ Cada gimnasio recibe su propio mensaje en `email_admin`  
✅ Si Hostinger te da puerto `465`, cambia `MAIL_PORT=465` y `MAIL_ENCRYPTION=ssl`  
✅ Debes usar la contraseña SMTP del buzón, no la contraseña normal del panel

---

## 🚀 Opción 1: Mailtrap (Recomendado - Gratis para desarrollo)

### Pasos:
1. **Regístrate** en [https://mailtrap.io](https://mailtrap.io)
2. **Verifica tu email** (llega al instante)
3. **Ve a Dashboard** > **Integrations** > **SMTP Settings**
4. **Copia tus credenciales:**
   - **Username**: `xxxxxxxxxx`
   - **Password**: `xxxxxxxxxx`

5. **Actualiza tu `.env`:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=tu_username_aqui
   MAIL_PASSWORD=tu_password_aqui
   MAIL_ENCRYPTION=tls
   ```

6. **Guarda y lista!** 

### Ventajas:
✅ Gratis  
✅ Sin límites de emails  
✅ Inbox de prueba integrado  
✅ Datos de prueba visibles en el dashboard  

---

## 🟦 Opción 2: Brevo (antes Sendinblue)

### Pasos:
1. Regístrate en [https://www.brevo.com](https://www.brevo.com)
2. Ve a **SMTP & API** > **SMTP**
3. Copia Username y Password
4. Configura en `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp-relay.brevo.com
   MAIL_PORT=587
   MAIL_USERNAME=tu_email@ejemplo.com
   MAIL_PASSWORD=tu_api_key
   MAIL_ENCRYPTION=tls
   ```

---

## 📨 Opción 3: Gmail (SMTP)

### Pasos:
1. Activa **2FA** en tu cuenta Gmail
2. Ve a [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
3. Genera una **App Password** (para Linux)
4. Copia la contraseña generada (16 caracteres sin espacios)
5. Configura en `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=tu_email@gmail.com
   MAIL_PASSWORD=tu_app_password
   MAIL_ENCRYPTION=tls
   ```

---

## 🧪 Verificar que funciona

Después de configurar, abre una terminal en el proyecto:

```bash
php artisan tinker
```

Luego dentro de tinker:
```php
Mail::raw('Test', function($message) {
    $message->to('tu_email@ejemplo.com')->subject('Test');
});
```

Deberías ver el email en el inbox del servicio que uses.

---

## 📝 Configuración actual en `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@saas-gimnasios.com"
MAIL_FROM_NAME="SaaS Gimnasios"
```

**Solo falta llenar `MAIL_USERNAME` y `MAIL_PASSWORD` con tus credenciales.**

---

## 🎯 Una vez configurado

Cuando crees un nuevo gimnasio:
1. ✅ El admin del gimnasio recibirá un **email real** con sus credenciales
2. ✅ Podrá ingresar al panel con esas credenciales
3. ✅ Se le pedirá cambiar la contraseña en el primer login

---

## ❓ Preguntas frecuentes

**¿El email llegará a spam?**  
Probablemente al principio. Mailtrap tiene buena reputación. Si usas Gmail app password, también será confiable.

**¿Puedo cambiar de servicio después?**  
Sí, solo actualiza las 5 líneas en `.env` y listo.

**¿Necesito reiniciar Laravel?**  
No, Laravel lee `.env` en cada request.

---

**¡Ahora configura tu email y vuelve a crear un gimnasio para probar!**
