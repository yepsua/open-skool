OpenSkool Guia de Instalaci�n
=============================

1) Requerimientos
-----------------

Este metodo de instalacion utiliza GIT para descargar el codigo fuente y sus dependencias.


2) Instalación
--------------

Ejecuta paso a paso los siguientes comandos.

``` bash
git clone --progress -v  "https://github.com/yepsua/open-skool" "open-skool"
```

``` bash
cd open-skool
```

``` bash
composer.phar selfupdate
```

Ejecuta el siguiente comando que instalara las dependencias de la aplicaci�n y luego
solocitara la configurai�n para la conexion a la Base de Datos.

``` bash
composer.phar install
```

El siguiente comando crea el esquema de Base de Datos, es muy importante que configures 
bien los datos de conexion a la Base de Datos ya que este comando los utiliza.

``` bash
app/console doctrine:schema:update --force
```

Ahora se cargan los datos basicos para que la aplicacion pueda funcionar 
(Fixtures).

``` bash
app/console doctrine:fixtures:load
```

Si quieres cargar datos de ejemplo de una Institucion Academica falsa puedes 
ejecutar el siguiente comando:

``` bash
$ app/console doctrine:fixtures:load  --fixtures=src/OpenSkool/StaticResourcesBundle/DataFixtures/Faker/ORM/ --append
```

Cargar datos de ejemplo de una Institucion falsa permite verificar mejor el funcionamiento
de la aplicacion. Se aconseja reailzar esta accion en una base de datos de prueba y no
en la que se utilizara en produccion. Este comando soo se podra ejecutar una vez ya que luego
de haberse creado la data por primera vez no se podran crear mas datos falsos por esta via debido
a las claves unicas en algunos campos de las tablas de Base de datos.

Por ultimo se crea el usuario administrador.

``` bash
app/console fos:user:create --super-admin admin admin@mail.com password
```

2) Ejecución
------------

Ahora puedes iniciar el servidor HTTP que estes usando y entrar a la siguiente ruta:

http://localhost/open-skool/app.php/admin 

Si tienes configurado el modulo "mod-rewrite" en el servidor HTTP puedes acceder directamente a la ruta:

http://localhost/open-skool/app.php/admin 

A partir de PHP 5.5 el lenguaje cuenta con un servidor HTTP para pruebas. 
Para correr la aplicacion en el servidor de PHP ejecuta el siguiente comando:

``` bash
php -S localhost:8000
```

Recuerde estar situado en la carpeta 'open-skool' o en la carpeta donde descargo el codigo de la aplicaci�n.