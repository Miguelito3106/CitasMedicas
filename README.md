# 📅 API de Gestión de Citas Médicas

API RESTful desarrollada en **Laravel 10** para administrar citas médicas,
médicos, pacientes, consultorios y horarios de atención.\
Incluye autenticación con **Laravel Sanctum** (token-based) para proteger rutas
y facilitar la integración con aplicaciones frontend o móviles.

---

## ✨ Características

✅ **Autenticación con tokens** mediante Laravel Sanctum.\
✅ **CRUD completo** de citas, médicos, pacientes, consultorios y horarios.\
✅ **Protección de rutas** con middleware `auth:sanctum`.\
✅ **Validación de datos** usando Form Requests.\
✅ **Respuestas en formato JSON** listas para consumo en frontends (Vue, React,
Angular, React Native).\
✅ **Compatibilidad con Postman** (colección incluida en el repositorio).\
✅ **Pruebas automáticas** para garantizar la calidad del código.

---

## ⚙️ Instalación y Configuración

1. **Clonar el repositorio:**


git clone https://github.com/tu-usuario/api-citas-medicas.git
cd api-citas-medicas

  Instalar dependencias:

bash
Copy code
composer install
Configurar variables de entorno:

bash
Copy code
cp .env.example .env
php artisan key:generate
Configura en .env la conexión a tu base de datos:

makefile
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citas_medicas
DB_USERNAME=root
DB_PASSWORD=
Ejecutar migraciones y seeders:

bash
Copy code
php artisan migrate --seed
Iniciar el servidor de desarrollo:

bash
Copy code
php artisan serve
La API estará disponible en:
👉 http://127.0.0.1:8000


🔑 Autenticación
La API usa Laravel Sanctum para autenticación basada en tokens.
Flujo típico:

Registro:
POST /api/register
Enviando name, email, password y password_confirmation.
Recibirás un token en la respuesta.

Login:
POST /api/login
Enviando email y password.
Recibirás un token de sesión.

Autenticación en requests:
Agregar en los headers:

makefile
Copy code
Authorization: Bearer TU_TOKEN
Accept: application/json
Cerrar sesión:
POST /api/logout
Elimina el token activo.



 Rutas Principales (Endpoints)
Método	Ruta	Descripción	Protegida
POST	/api/register	Registro de usuario	
POST	/api/login	Inicio de sesión	
POST	/api/logout	Cerrar sesión	
GET	/api/citas	Listar citas	
POST	/api/citas	Crear cita	✅
GET	/api/citas/{id}	Mostrar cita específica	✅
PUT	/api/citas/{id}	Actualizar cita	✅
DELETE	/api/citas/{id}	Eliminar cita	✅
...	/api/medicos	CRUD de médicos	✅
...	/api/pacientes	CRUD de pacientes	✅
...	/api/consultorios	CRUD de consultorios	✅
...	/api/horarios	CRUD de horarios	✅

🧪 Pruebas Automáticas
Ejecuta las pruebas de la API:

bash
Copy code
php artisan test
Ejemplo de prueba de creación de cita (tests/Feature/CitaTest.php):

php
Copy code
public function test_usuario_autenticado_puede_crear_cita()
{
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => "Bearer $token"])
        ->postJson('/api/citas', [
            'paciente_id' => 1,
            'medico_id' => 1,
            'consultorio_id' => 1,
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00',
        ]);

    $response->assertStatus(201);
}



 Herramientas de Desarrollo
Laravel 10

Sanctum (autenticación)

Eloquent ORM

PHPUnit (pruebas)

Postman (colección incluida)



 Uso con Postman
Importa el archivo postman_collection.json.

Ejecuta la request Register o Login para obtener el token.

Configura la variable {{token}} en Postman.

Prueba los endpoints protegidos (Citas, Médicos, etc.).

 Despliegue en Producción
Configura un servidor con PHP 8.2+, Composer y MySQL.

Configura el archivo .env con credenciales de producción.

Ejecuta:

bash
Copy code
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan migrate --force
Usa un servidor web como Nginx o Apache apuntando a /public.

 Licencia
Este proyecto está bajo la licencia MIT.
Puedes usarlo, modificarlo y distribuirlo libremente.
```
