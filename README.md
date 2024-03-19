<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Esta version de laravel funciona con php 8.1

1) Instalar dependecias composer:
    composer install

2) editar la configuración de la base de datos en el archivo .env (Si tiene clave y nombre de la misma)

3) limpiar cache:
    php artisan config:clear
    php artisan optimize:clear


4) Ejecutar los siguientes comandos : 

- Base de datos nueva 
      php artisan migrate --seed

-Base de datos existente
     php artisan migrate:fresh --seed

-Documentacion API
    php artisan l5-swagger:generate

5) Iniciar servidor

   php artisan serve

Ingresar a la documentacion http://127.0.0.1:8000/api/documentation, o realizar pruebas en thunder client o Post man
la funcionalidad de la api es la siguiente: 
 - tenemos funcionalidad de registro de usuario, login, logout, refresh, las cuales se realizan sin autentificacion
 - el login genera un bearer token que es usado para autentificar en las pruebas API.
 - las funciones de perfil de usuario, usuarios, software y servicios necesitan un token para acceder de manera segura a la información.



