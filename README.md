# Gundam Store - Desafío Práctico 02 (DSS404)

## Características Técnicas

- **Seguridad Criptográfica:** Implementación de `password_hash()` (algoritmo BCRYPT) para el registro y `password_verify()` para el inicio de sesión.
- **Persistencia de Datos:** Manejo avanzado de `$_SESSION` para el carrito de compras y la autenticación de usuarios.
- **Acceso a Datos Robusto:** Uso de **PDO (PHP Data Objects)** con consultas preparadas para prevenir inyecciones SQL.
- **Integridad de Transacciones:** El proceso de pago utiliza `beginTransaction()` y `rollBack()` para asegurar que el stock solo se descuente si el pago se registra correctamente.
- **Experiencia de Usuario (UX):** URLs amigables (sin extensión .php) y página de error 404 personalizada con temática de la serie.

## Stack Tecnológico

* **Backend:** PHP 8.x
* **Base de Datos:** MariaDB / MySQL
* **Servidor:** Apache (con mod_rewrite activado)
* **Frontend:** HTML5 / CSS3 (Diseño responsivo y temático)

## Estructura del Hangar (Archivos)

- `catalogo`: Galería interactiva de Mobile Suits.
- `carrito`: Gestión de unidades seleccionadas y cálculo de totales.
- `finalizar_compra`: Motor de transacciones y actualización de inventario.
- `panel_tienda`: Historial de adquisiciones del piloto.
- `login` / `registro`: Puertas de acceso seguras.
- `404`: Protocolo de emergencia para rutas no encontradas.

## Guía de Despliegue

1. **Base de Datos:** Importar el archivo `Database/tienda_virtual.sql` en phpMyAdmin.
2. **Imágenes:** Asegurar que las 5 imágenes de los modelos (RX-78-2, Aerial, Unicorn, Wing y Strike Freedom) estén en la carpeta `/img`.
3. **Servidor Local:** Colocar el proyecto en `htdocs` y verificar que el archivo `.htaccess` esté presente en la raíz.
4. **Configuración:** Revisar `conexion.php` para ajustar las credenciales de la base de datos si es necesario.

## Equipo de Pilotos
* **Abel Stuardo Lopez Velasquez** - LV231728

---
© 2026 - Universidad Don Bosco, Facultad de Ingeniería.
