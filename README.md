<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/tu-usuario/tu-repositorio/actions">
    <img src="https://github.com/tu-usuario/tu-repositorio/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

## Descripción

Este es un sistema de **Gestión de clientes** implementado con Laravel, diseñado para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) de manera sencilla. La API proporciona una interfaz para gestionar información básica de clientes como su nombre, apellido, correo electrónico y teléfono. 

Está construida usando **Laravel 12**, y su documentación está disponible a través de **Swagger** para facilitar la interacción y el uso de la API.

## Características

- **Operaciones CRUD** para clientes.
- **Documentación** interactiva con **Swagger**.
- API simple y eficiente para integrar en aplicaciones.

## Capturas de Pantalla

### 1. Rutas Disponibles

Aquí se muestran las rutas disponibles en la API.

![Rutas disponibles](sw1.png)

### 2. Descripción de un Endpoint

En esta captura se muestra la descripción de un endpoint y los detalles sobre sus parámetros y respuestas.

![Descripción de un endpoint](sw22.png)

## Instalación

### Requisitos

- **PHP** 8.1 o superior
- **Composer**
- **Laravel 12**

### Pasos para la instalación

1. Clona el repositorio:

   

2. Entra al directorio del proyecto:

   

3. Instala las dependencias con Composer:

    ```bash
    composer install
    ```

4. Configura el archivo `.env`. Copia el archivo `.env.example` a `.env` y ajusta las configuraciones de tu base de datos:

    ```bash
    cp .env.example .env
    ```

    Luego, edita el archivo `.env` con tus credenciales de base de datos.

5. Corre las migraciones para crear las tablas necesarias:

    ```bash
    php artisan migrate
    ```

## Uso de la API


### Ejemplos de Endpoints

#### 1. Crear un Cliente

- **Método:** `POST`
- **Endpoint:** `/api/cliente`
- **Descripción:** Crea un nuevo cliente.
- **Request Body:**
    ```json
    {
        "nombre": "Juan",
        "apellido": "Pérez",
        "email": "juan.perez@email.com",
        "telefono": 1122334455,
        "dni": 40123456
    }
    ```
- **Response:**
    ```json
    {
        "status": true,
        "message": "Cliente creado con éxito",
        "data": {
            "id": 1,
            "nombre": "Juan",
            "apellido": "Pérez",
            "email": "juan.perez@email.com",
            "telefono": 1122334455,
            "dni": 40123456,
            "created_at": "2025-05-07T14:48:00.000Z",
            "updated_at": "2025-05-07T14:48:00.000Z"
        }
    }
    ```

#### 2. Obtener Cliente por DNI

- **Método:** `GET`
- **Endpoint:** `/api/cliente?dni=40123456`
- **Descripción:** Obtiene los datos de un cliente basado en su DNI.
- **Response:**
    ```json
    {
        "status": true,
        "message": "Cliente encontrado",
        "data": {
            "id": 1,
            "nombre": "Juan",
            "apellido": "Pérez",
            "email": "juan.perez@email.com",
            "telefono": 1122334455,
            "dni": 40123456,
            "created_at": "2025-05-07T14:48:00.000Z",
            "updated_at": "2025-05-07T14:48:00.000Z"
        }
    }
    ```

#### 3. Actualizar un Cliente

- **Método:** `PUT`
- **Endpoint:** `/api/cliente`
- **Descripción:** Actualiza la información de un cliente.
- **Request Body:**
    ```json
    {
        "dni": 40123456,
        "new_dni": 40123457,
        "nombre": "Juan Carlos",
        "apellido": "Pérez",
        "email": "juan.carlos@email.com",
        "telefono": 1122334466
    }
    ```
- **Response:**
    ```json
    {
        "status": true,
        "message": "Cliente actualizado con éxito",
        "data": {
            "id": 1,
            "nombre": "Juan Carlos",
            "apellido": "Pérez",
            "email": "juan.carlos@email.com",
            "telefono": 1122334466,
            "dni": 40123457,
            "created_at": "2025-05-07T14:48:00.000Z",
            "updated_at": "2025-05-07T15:00:00.000Z"
        }
    }
    ```

#### 4. Eliminar un Cliente

- **Método:** `DELETE`
- **Endpoint:** `/api/cliente?dni=40123456`
- **Descripción:** Elimina un cliente basado en su DNI.
- **Response:**
    ```json
    {
        "status": true,
        "message": "Cliente eliminado con éxito",
        "data": {
            "id": 1,
            "nombre": "Juan",
            "apellido": "Pérez",
            "email": "juan.perez@email.com",
            "telefono": 1122334455,
            "dni": 40123456,
            "created_at": "2025-05-07T14:48:00.000Z",
            "updated_at": "2025-05-07T14:48:00.000Z"
        }
    }
    ```



