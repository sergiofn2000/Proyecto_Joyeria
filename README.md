
# Proyecto de Joyería en Local (Sin Hosting)
Bienvenido a Joyería Elegante, tu destino digital para descubrir y adquirir exquisitas piezas de joyería. Esta plataforma web, diseñada con un enfoque en la elegancia y la facilidad de uso, es completamente receptiva, brindando una experiencia de compra fluida en dispositivos de todos los tamaños.

## Características Principales:
### 1. Tienda Virtual Completa
Explora nuestra extensa colección de joyas, que abarca desde elegantes collares hasta deslumbrantes anillos. Organizadas en categorías intuitivas, encontrarás fácilmente la joya perfecta para cualquier ocasión.

### 2. Carrito de Compras y Lista de Favoritos
Añade tus joyas favoritas al carrito de compras o guárdalas en tu lista de favoritos para una experiencia de compra personalizada. Nunca pierdas de vista esas piezas que te robaron el corazón.

### 3. Cuenta de Usuario
Crea tu cuenta personalizada para acceder a funciones exclusivas. Administra tus pedidos, guarda tus direcciones de envío y mantén un registro de tus compras anteriores.

### 4. Autenticación Segura
Inicia sesión de manera segura para acceder a todas las funcionalidades de la plataforma. Tu información personal está protegida mediante sólidas medidas de seguridad.

### 5. Proceso de Pago Seguro con Stripe
Realiza pagos sin preocupaciones gracias a nuestra integración con Stripe, una reconocida pasarela de pago. Tu información financiera está protegida y tus transacciones son seguras.

### 6. Experiencia de Usuario Intuitiva y Responsive
Diseñada con el usuario en mente, nuestra plataforma ofrece una navegación fluida y una interfaz intuitiva, adaptándose perfectamente a dispositivos móviles, tabletas y computadoras de escritorio.

## Estado Actual del Proyecto
Este proyecto es un ejemplo de una página web para una joyería o cualquier tienda en línea que vende artículos. Actualmente, no está alojado en un servidor de hosting debido a los costos, pero en el futuro, se alojará en un servidor para mostrarlo adecuadamente en un portafolio web.

## Guía de Instalación

### Paso 1: Instalación de XAMPP

1. Descarga XAMPP desde el sitio web oficial: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).

2. Sigue las instrucciones de instalación proporcionadas en el sitio web para tu sistema operativo (Windows, macOS, Linux).

3. Una vez instalado XAMPP, inicia el servidor Apache y MySQL desde el Panel de Control de XAMPP.

### Paso 2: Clonar el Repositorio

1. Abre tu terminal o línea de comandos.
  
2. Navega a la ubicación donde deseas clonar el repositorio de tu proyecto.
  
3. Ejecuta el siguiente comando para clonar el repositorio desde GitHub:
    
   
   git clone https://github.com/sergiofn2000/Proyecto_Joyeria.git
### Paso 3: Configuración del VirtualHost

1. Abre el archivo C:\Windows\System32\drivers\etc\hosts en un editor de texto como administrador.

2. Agrega la siguiente línea al archivo y guárdalo:

  127.0.0.1:8080 joyeria.local.com

3. Abre el archivo C:\xampp\apache\conf\extra\httpd-vhosts.conf y agrega el siguiente bloque:

<VirtualHost joyeria.local.com:8080>
  DocumentRoot "C:\xampp\htdocs\TU RUTA"
  ServerAlias joyeria.local.com
  <Directory "C:\xampp\htdocs\TU RUTA">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>

4.Abre el Panel de Control de XAMPP y haz clic en "Config" para Apache.

5.Selecciona "httpd.conf" y agrega la siguiente línea al archivo:

Listen 8080

6.Guarda los cambios.

7.Reinicia el servidor Apache desde el Panel de Control de XAMPP.



### Paso 4: Importar la Base de Datos

1.Abre un navegador y accede a http://localhost/phpmyadmin/.

2.Haz clic en "Importar" y selecciona el archivo joyeria.sql del repositorio clonado.


### Paso 5: Acceder al Proyecto

Ahora, puedes acceder a tu proyecto en tu navegador utilizando la siguiente URL:

http://joyeria.local.com:8080

