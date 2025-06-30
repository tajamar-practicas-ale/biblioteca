# Biblioteca - Bibliotech

Actualizar el sistema operativo e instalar actualizaciones:  
`sudo apt update && sudo apt upgrade -y`

Instalar Apache, PHP y extensiones necesarias junto con MySQL y git:  
`sudo apt install apache2 php libapache2-mod-php php-mbstring php-xml php-zip unzip php-mysql mysql-server git -y`

Instalar Composer globalmente:  
`curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer`

Clonar el repositorio y acceder a la carpeta:  
```.bash
cd /var/www/
git clone https://github.com/tajamar-practicas-ale/biblioteca.git  
cd biblioteca
```

Instalar dependencias PHP con Composer:  
`composer install`

Copiar el archivo de entorno y modificar variables de base de datos:  
`cp .env.example .env`

Editar el archivo .env para que contenga:  
``` .env
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=biblioteca  
DB_USERNAME=root  
DB_PASSWORD= (no poner contraseña en el repositorio ni subirla al control de versiones; establecerla localmente)
```

Acceder a MySQL para configurar usuario y base de datos si es necesario:  
`sudo mysql`
``` sql
CREATE DATABASE IF NOT EXISTS biblioteca;  
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'tu_contraseña_segura';  
FLUSH PRIVILEGES;  
EXIT;
```
Restaurar backup:  
`mysql -u tu_usuario_mysql -p biblioteca < infrastructure/backup_biblioteca.sql`

Generar la clave de aplicación Laravel:  
`php artisan key:generate`

Dar permisos correctos para que Apache pueda escribir en carpetas importantes:  
`sudo chown -R www-data:www-data storage bootstrap/cache`

Ejecutar migraciones y cargar datos iniciales si existen:  
`php artisan migrate --seed`

Configurar Apache copiando el archivo de configuración y habilitando el sitio:  
`sudo cp infrastructure/infras.conf /etc/apache2/sites-available/biblioteca.conf`  

Editar el archivo `/etc/apache2/sites-available/biblioteca.conf` para actualizar la IP pública de la instancia EC2 antes de habilitar el sitio.
``` bash
sudo a2ensite biblioteca.conf  
sudo a2enmod rewrite  
sudo systemctl restart apache2
```

(Opcional) Deshabilitar el sitio por defecto de Apache: 
``` bash
sudo a2dissite 000-default.conf  
sudo systemctl reload apache2
```
El proyecto debería estar accesible desde el navegador apuntando al dominio o IP configurados en Apache.

## Modelo de datos (Diagrama ER)

El esquema de la base de datos está definido en el archivo [`infrastructure/schema.dbml`](./infrastructure/schema.dbml). Este archivo contiene la definición en formato DBML para usar en [dbdiagram.io](https://dbdiagram.io) y otras herramientas compatibles.

### Cómo visualizar el diagrama

1. Accede a [dbdiagram.io](https://dbdiagram.io).  
2. Importa el archivo `schema.dbml` ubicado en la carpeta `infrastructure` del proyecto.  
3. Visualiza y edita el diagrama según necesites.

---

## Organización del proyecto

- `/infrastructure/infras.conf`: configuración de Apache.  
- `/infrastructure/schema.dbml`: definición del modelo entidad-relación (ER) en DBML.
- `/infrastructure/backup_biblioteca.sql`: backup de la base de datos (preferiblemente no ponderlo en el repo).
