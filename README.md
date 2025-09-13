# 🏋️ Proyecto reservas-gym-ia

## 1. Entorno de desarrollo
- **Sistema operativo:** Ubuntu (máquina virtual).  
- **Editor:** Visual Studio Code.  
- **Servidor de desarrollo:** Laravel con `php artisan serve`.  
- **Gestor de dependencias:** Composer.  
- **Gestor de BD:** phpMyAdmin (sobre MariaDB/MySQL).  

---

## 2. Instalación del proyecto
```bash
cd ~
mkdir reservas-gym-ia
cd reservas-gym-ia
composer create-project laravel/laravel .
php artisan serve

---

## 3. Bases de datos

Base creada desde phpMyAdmin:

reservas_gym_ia


Collation: utf8mb4_unicode_ci.

Migraciones ejecutadas:

users

password_resets

failed_jobs

personal_access_tokens (Sanctum)


## 4. GitHub
Proyecto inicializado con git init.

.gitignore configurado para excluir:

/vendor, /node_modules, .env, /storage/logs, etc.

Proyecto subido a GitHub en rama main


##5. Autenticación (Sanctum)

Instalación:

composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate


Tabla creada: personal_access_tokens.

##6. Primeros endpoints de prueba
🔹 GET /api/ping

Ruta (routes/api.php):

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});


Respuesta de ejemplo:

{ "message": "pong" }


Probado en navegador y Postman (sin headers ni body).

##7. Controladores

Creado AuthController en app/Http/Controllers/:

php artisan make:controller AuthController


Métodos implementados:

register → registro de usuario en tabla usuarios con validación y contraseña cifrada.

login → devuelve token Sanctum si credenciales correctas.

logout → invalida el token actual.


