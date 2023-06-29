Advanced Search

El propósito de este plugin es crear una búsqueda avanzada por token de usuario entre las encuestas activas.

En primera instancia, se crea una clasa que extiende desde una clase PluginBase los elementos basicos del plugin y ciertas librerias

Luego se inicializa los valores del nombre del plugin, la descripcion, el almacenamiento y la version.

El funcionamiento de lime para temas de metodos y redireccionamiento funciona atravez de urls, estos secrean mediante la funcion de createUrl, en el cual se define el plugin y el nombre de metodo a realizar.

Para poder usar css y js se deben inscribir con la funcion register'Tipo de archivo'File 

La manera en la que se hacen las querys es mediante el comando db el cual hace referencia a la llamada de la base de datos y luego el createComand, donde se hace la query.

## To Do

* falta revisar el problema con la desaparicion del menu principal de Advanced Search en la pestania de administracion de extenciones, ya que esta no desaparece (estudiar evento beafore desactivate)
* falta estudiar el error de return en el metodo de getSurveys
* afinar el js 

### Installation

Para instalar este plugin solo debe descomprimir el archivo zip en la carpeta plugins ubicada en la siguiente ruta: /CarpetaRaizDeLime/plugins

### Requirements

* LimeSurvey 3.27 o actual
