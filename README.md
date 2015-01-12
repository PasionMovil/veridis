Veridis
=======

Modificación en los archivos del tema
-------------------------------------

Los siguientes archivos del tema fueron modificados ya que no había forma de
realizar tales cambios con las opciones del mismo. Tener en cuenta a la hora
de realizar actualizaciones.

**[meganews/single.php](site/wp-content/themes/meganews/single.php):**
  - Se eliminó el resumen al principio del post ([5e918f5b91](https://github.com/inkatze/veridis/commit/5e918f5b91672412667913920e72f35ef2540ccd)).
  - Se agregaron lo terminos de búsqueda de Fuzzy SEO ([a1d14c673c](https://github.com/inkatze/veridis/commit/a1d14c673c79ef5f79b63037693f8f9f0782bc8c)).
  - Se comentó la ficha del autor ([17ca6b3dc5](https://github.com/inkatze/veridis/commit/17ca6b3dc5dd15d05c9a07327e1719df6eead2c9)).
  - Se comentó el bloque de comentarios ([c77fda6772](https://github.com/inkatze/veridis/commit/c77fda6772eafbd01ad466f8b374bddef4640368)).

**[meganews/css/media.css](site/wp-content/themes/meganews/css/media.css):**
  - Se comentó el código css y se eliminaron los espacios en blanco extra ([eac63d641f](http://github.com/inkatze/veridis/commit/eac63d641f9aef66508ba55c4f9f0115d135584b)).
  - Se eliminó el css ([56d701a761](https://github.com/inkatze/veridis/commit/56d701a7615808da2568d59cde2dced5bb5ded06)).

**[meganews/header.php](site/wp-content/themes/meganews/header.php):**
  - Se agregó una redirección a navegadores WAP ([5b614374e3](https://github.com/inkatze/veridis/commit/5b614374e37ea5d1b0c13bf08dcbb981afd7225e)).

**[wap.php](site/wap.php):**
  - Versión móvil del sitio para navegadores WAP ([9839847f6f](https://github.com/inkatze/veridis/commit/9839847f6f237c9aec169a7ae77b477b0a4ce22c)).

Pasos para deshabilitar el contenido responsivo
-----------------------------------------------

Aparte de eliminar el contenido de media.css, es necesario ir a
Settings >> Visual Composer >> Disable responsive content elements.
Únicamente deshabilitando esta opción no es suficiente para suprimir el
contenido responsivo, es necesario eliminar el contenido de media.css.
