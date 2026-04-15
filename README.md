# Tienda Virtual - Desafío Práctico 02

Este proyecto consiste en un sistema de autenticación seguro para una tienda virtual básica, desarrollado para la asignatura de **Desarrollo de Aplicaciones Web con Software Interpretado en el Servidor (DSS404)**.

El sistema permite el registro de clientes, el inicio de sesión mediante credenciales cifradas y el acceso a un panel privado con persistencia de datos a través de variables de sesión.

## Funcionalidades Principales

- **Registro de Usuarios:** Captura de datos (nombre, correo y clave) con validación del lado del servidor.
- **Seguridad Criptográfica:** Implementación de `password_hash()` para el almacenamiento de contraseñas y `password_verify()` para la autenticación.
- **Gestión de Sesiones:** Uso de `session_start()` para proteger rutas privadas y personalizar la experiencia del usuario.
- **Acceso a Datos:** Conexión robusta mediante **PDO (PHP Data Objects)** con manejo de excepciones.

## Tecnologías Utilizadas

* **Lenguaje:** PHP 8.x
* **Base de Datos:** MySQL / MariaDB
* **Interfaz:** HTML5 y CSS3 (Diseño responsivo)
* **Entorno:** XAMPP / GitHub Desktop / VS Code

## Requisitos e Instalación

### 1. Base de Datos
Importa el siguiente script en tu gestor (phpMyAdmin) para crear la estructura necesaria:

```sql
-- 1. Crear la base de datos y decirle a MySQL que la use
CREATE DATABASE IF NOT EXISTS tienda_virtual;
USE tienda_virtual;

-- 2. Crear la tabla principal de usuarios (clientes)
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL -- 255 es el estándar seguro para password_hash
);

-- 3. Crear la tabla para el resumen de compras (requisito de la guía)
CREATE TABLE compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    producto VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Llave foránea: Si borras un cliente, se borran sus compras
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE
);

