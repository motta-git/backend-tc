# Backend - Administrador de Servidores

Este es el backend de la API para la aplicación de administración de servidores, desarrollado con Laravel 12. Provee todos los endpoints necesarios para gestionar la lista de servidores.

---

## Características Principales

- API RESTful para operaciones CRUD (Crear, Leer, Actualizar, Eliminar) de servidores.
- Subida y redimensionamiento automático de imágenes a 300x300 píxeles usando la librería GD de PHP.
- Ordenamiento de la lista de servidores guardado en el backend.
- Validación de datos, incluyendo formato de IPv4 y mensajes de error en español.
- Soporte para base de datos SQLite.

---

## Requisitos

- PHP >= 8.3
- Composer
- Extensión de PHP: GD, pdo_sqlite, mbstring

---

## Instalación y Ejecución Local

1. Clonar el repositorio y navegar a la carpeta del backend:
   ```
   cd backend-tc
2. Copiar el archivo de entorno:
    ```
    cp .env.example .env
    ```
3. Instalar las dependencias de Composer:

    ```
    composer install
    ```
4. Generar la clave de la aplicación:
    ```
    php artisan key:generate
    ```
5. Crear el archivo para la base de datos SQLite:

    ```
    touch database/database.sqlite
    ```
6. Ejecutar las migraciones para crear las tablas en la base de datos:

    ```
    php artisan migrate
    ```

7. Crear el enlace simbólico para el almacenamiento de archivos públicos:

    ```
    php artisan storage:link
    ```
8. Iniciar el servidor de desarrollo:

    ```
    php artisan serve
    ```

Endpoints de la API

- GET	/api/servers	Obtiene la lista de todos los servidores.
- POST	/api/servers	Crea un nuevo servidor.
- GET	/api/servers/{id}	Obtiene los datos de un servidor específico.
- PUT	/api/servers/{id}	Actualiza un servidor existente.
- DELETE	/api/servers/{id}	Elimina un servidor.
- POST	/api/servers/update-order	Guarda el nuevo orden de la lista.


# Testing
Para ejecutar la suite de tests de PHPUnit, corre el siguiente comando:

```
php artisan test
```
