# Video Rentals

Aplicación que permite el alquiler de peliculas en formato Dvd.
## Comenzando 🚀

Para obtener una copia del proyecto ejecuta:

```
https://github.com/agasdev/video-rentals.git
```


### Instalación 🔧

Para la ejecución del proyecto será necesario tener instalado tando **docker**, como **docker-compose**

```
$ docker-compose up -d --build 

$ docker exec -it  symfony-docker_php_1 bash

$ composer install

$ php bin/console doctrine:database:create

$ php bin/console doctrine:migrations:migrate

$ php bin/console assets:install

$ php bin/console cache:clear
```

Para acceder al panel de administrador, accede a:
```
http://localhost:8000/admin/
```

Para acceder a la base de datos, accede a:
```
http://localhost:8081/
```

Para acceder al frontend, accede a:
```
http://localhost:3000/
```

## Ejecutando las pruebas ⚙️

```
$ docker exec -it  symfony-docker_php_1 bash

$ bin/phpunit tests/
```

## Construido con 🛠️

* [Symfony 5](https://symfony.com/doc/current/index.html) - El framework

---