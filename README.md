# 🍔 EntreTejas: Sistema de Gestión de Restaurante

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## 📖 Descripción del Proyecto

**EntreTejas** es un aplicativo web desarrollado en **PHP** con base de datos **MySQL**, diseñado para optimizar la gestión operativa del restaurante "EntreTejas". El sistema ofrece una solución integral para la administración de pedidos, control de usuarios y visualización del menú en línea.

La plataforma se estructura en dos roles principales para garantizar la seguridad y eficiencia del negocio: el **Administrador**, quien tiene acceso a un panel de control avanzado para gestionar pedidos en tiempo real, administrar cuentas de usuarios y supervisar las ventas; y el **Usuario/Cliente**, quien puede registrarse, explorar el menú y realizar pedidos de forma intuitiva.

---

## 🚀 Módulos y Funcionalidades

### 🛡️ Panel de Administración (Back-Office)
* **Gestión de Pedidos en Vivo:** Dashboard interactivo que permite visualizar y filtrar los últimos pedidos realizados por los clientes.
* **Administración de Usuarios:** Herramientas para gestionar tanto las cuentas de administradores como de clientes registrados.
* **Control de Ventas:** Visualización detallada de transacciones, incluyendo productos vendidos, totales y métodos de pago.
* **Interfaz Personalizable:** Opción de "Modo Oscuro" para mejorar la experiencia visual del administrador.

### 🍽️ Portal del Cliente (Front-Office)
* **Menú Digital:** Catálogo visual de productos (Comidas, Bebidas, Postres) disponible en línea.
* **Registro y Autenticación:** Sistema seguro de login y registro para nuevos clientes.
* **Realización de Pedidos:** Flujo completo de compra, desde la selección de productos hasta la confirmación del pedido.

---

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP Nativo (Sesiones, Conexión MySQLi)
* **Base de Datos:** MySQL
* **Frontend:** HTML5, CSS3 (Diseño responsivo con Bootstrap 5)
* **Scripting:** JavaScript (Interactividad y validaciones)
* **Librerías:** FontAwesome (Iconos)

---

## 👥 Equipo de Desarrollo

Este proyecto fue desarrollado por:

| Integrante | Rol |
| :--- | :--- |
| **Gerardo Martínez** | Desarrollador Fullstack |
| **Alex Martínez** | Desarrollador Fullstack |
| **Osaki Arévalo** | Desarrollador Fullstack |

---

## 👥 Credenciales de Prueba

Para facilitar la evaluación del sistema, se proporcionan las siguientes credenciales predeterminadas:

| Rol | Correo | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `m@m.com` | `test` |
| **Usuario** | `my@m.com` | `test` |

---

## ⚙️ Instalación y Despliegue

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/usuario/entretejas-project.git](https://github.com/usuario/entretejas-project.git)
    ```
2.  **Configurar Base de Datos:**
    * Importar el archivo `entretejas.sql` en tu servidor MySQL (phpMyAdmin o Workbench).
    * Configurar la conexión en `EntreTejas/config/conexion.php` con tus credenciales locales.
3.  **Despliegue:**
    * Colocar la carpeta del proyecto en el directorio raíz de tu servidor web (ej. `htdocs` en XAMPP o `www` en WAMP).
4.  **Ejecutar:**
    * Acceder desde el navegador a `http://localhost/EntreTejas/`

---
© 2024 EntreTejas - Sabor y Tradición Digital.
