
# Proyecto de Joyería en Local (Sin Hosting)
El Proyecto Joyería no solo se distingue por su elegante diseño y eficiencia técnica, sino también por la implementación de handlers especializados para salvaguardar la seguridad y gestionar procesos clave:
## Detalles
### Frontend Avanzado: 
Desarrollado con JavaScript, CSS, Bootstrap y jQuery para lograr un diseño atractivo.

### Backend Eficiente: 
PHP puro impulsa la lógica del servidor, garantizando un rendimiento sólido y una gestión eficiente de los datos.

### Base de Datos MySQL: 
La base de datos MySQL respalda la gestión eficiente de productos, cuentas de usuario y pedidos, proporcionando un almacenamiento fiable.

###  Seguridad Anti-SQL Injection: 
Se ha implementado un handler personalizado en PHP para evitar SQL injection. Este handler proporciona funciones seguras para consultas y ejecuciones, protegiendo eficazmente la integridad de la base de datos.

### Gestión de Pagos Segura: 
Se implementa la API de Stripe para garantizar transacciones seguras y sin preocupaciones, protegiendo la información financiera de los usuarios.

### Interactividad AJAX: 
La tecnología AJAX se emplea para una experiencia de usuario más fluida, permitiendo la carga dinámica de la tienda, el carrito y otras secciones sin recargar la página.

### Routing Dinámico: 
Se implementan rutas para transformar la página en una single page application (SPA), mejorando la velocidad y la eficiencia de la navegación.

### Handler de Correo Electrónico: 
Se ha implementado un handler de correo electrónico que utiliza la librería PHPMailer para gestionar y enviar correos electrónicos de manera eficiente y segura.

### Handler de Token: 
Un handler de token personalizado permite la creación, verificación y cancelación segura de tokens. Este mecanismo garantiza la seguridad en procesos como restablecimiento de contraseña y otras operaciones sensibles.

### Estado Actual del Proyecto:
Aunque actualmente no se encuentra alojado en un servidor de hosting, este proyecto está listo para demostrar su funcionalidad en un entorno de producción, proporcionando una solución completa para una tienda de joyería en línea.


## Guía de Instalación

### Paso 1: Instalación de XAMPP

1. Descarga XAMPP desde el sitio web oficial: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).

2. Sigue las instrucciones de instalación proporcionadas en el sitio web para tu sistema operativo (Windows, macOS, Linux).

3. Una vez instalado XAMPP, inicia el servidor Apache y MySQL desde el Panel de Control de XAMPP.

### Paso 2: Clonar el Repositorio

1. Abre tu terminal o línea de comandos.
  
2. Navega a la ubicación donde deseas clonar el repositorio de tu proyecto.
  
3. Ejecuta el siguiente comando para clonar el repositorio desde GitHub:
    
   ```
   git clone https://github.com/sergiofn2000/Proyecto_Joyeria.git
   ```
   
### Paso 3: Configuración del VirtualHost

1. Abre el archivo C:\Windows\System32\drivers\etc\hosts en un editor de texto como administrador.

2. Agrega la siguiente línea al archivo y guárdalo:
```
  127.0.0.1:8080 joyeria.local.com
```
3. Abre el archivo C:\xampp\apache\conf\extra\httpd-vhosts.conf y agrega el siguiente bloque:

```
<VirtualHost joyeria.local.com:8080>
  DocumentRoot "C:\xampp\htdocs\TU RUTA"
  ServerAlias joyeria.local.com
  <Directory "C:\xampp\htdocs\TU RUTA">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>
```
4.Abre el Panel de Control de XAMPP y haz clic en "Config" para Apache.

5.Selecciona "httpd.conf" y agrega la siguiente línea al archivo:
```
Listen 8080
```
6.Guarda los cambios.

7.Reinicia el servidor Apache desde el Panel de Control de XAMPP.



### Paso 4: Importar la Base de Datos

1.Abre un navegador y accede a http://localhost/phpmyadmin/.

2.Haz clic en "Importar" y selecciona el archivo joyeria.sql del repositorio clonado.


### Paso 5: Acceder al Proyecto

Ahora, puedes acceder a tu proyecto en tu navegador utilizando la siguiente URL:
```
http://joyeria.local.com:8080
```
