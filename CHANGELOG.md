# Changelog
Todos los cambios notables en este proyecto se documentarán en este archivo.

El formato se basa en [Mantenga un Changelog](http://keepachangelog.com/es-ES/1.0.0/)
y este proyecto se adhiere al [Versionado Semántico](https://semver.org/lang/es/).

## [Unreleased]
### Añadido
- Se añade un registro de cambios al proyecto (CHANGELOG).

## [1.2.1] - 2017-12-20
### Añadido
- Se añade la sección
[Proyecto de demostración](https://github.com/cokecancino/laravel-flow#proyecto-de-demostraci%C3%B3n)
al README.

Es un repositorio de ejemplo del package implementado en Laravel 5.5
con el objetivo de tener una mayor comprensión de cómo utilizarlo.

## [1.2] - 2017-12-20
### Añadido
- Se actualiza la API de Flow a la versión 1.4

  - Se añade la opción Multicaja en la descripción del medio de pago
en el archivo de configuración.

  - Se añade la posibilidad de definir la página de retorno en el
archivo de configuración.

> La página de retorno solo aplica al medio de pago Multicaja.
Corresponde a la página donde volverá el cliente una vez que generó
el cupón de pago. Recomendamos que dicha URL sea la página principal
de tu tienda virtual.

Archivo de configuración: `config/flow.php`

```php
…
    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la URL de su página de retorno
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | 'http://www.comercio.cl',
    | ['url' => 'flow/retorno'],
    | ['route' => 'flow.retorno'],
    | ['action' => 'FlowController@retorno'],
    |
    */

    'url_retorno' => ['url' => '/'],
…
```

**Importante:** Debes volver a publicar el archivo de configuración
para reflejar los cambios:
```sh
$ php artisan vendor:publish --provider="CokeCancino\LaravelFlow\FlowServiceProvider" --force
```

### Cambiado
- Se han actualizado y mejorado los ejemplos de la sección
[Utilización](https://github.com/cokecancino/laravel-flow#utilizaci%C3%B3n)
en el README.

## [1.1.3] - 2016-11-11
### Cambiado
- Se actualiza la URL "Excluye la protección CSRF" en el README.

## [1.1.2] - 2016-11-11
### Arreglado
- Se corrige la URL "Kit de Integración de Flow" en el README.

## [1.1.1] - 2016-03-12
### Añadido
- Se añade la sección
[Utilización](https://github.com/cokecancino/laravel-flow#utilizaci%C3%B3n)
al README, con el objetivo de ejemplificar las diferencias de su
utilización dentro de Laravel.

## [1.1] - 2016-02-08
### Añadido
- Se añade la posibilidad de ingresar URLs absolutas en las opciones
del archivo de configuración.

## [1.0] - 2016-02-05
### Cambiado
- Se ha traducido al español el README y la descripción de `composer.json`.

### Eliminado
- Se elimina la tasa de comisión de Flow (variable `tasa_default`) de
la API de Flow y del archivo de configuración, ya que ésta ahora se
define desde el sitio web de Flow.

## [0.1.1] - 2016-02-05
### Añadido
- Se añaden las instrucciones de instalación al README.

### Cambiado
- Se mejora el formato de descripción del archivo de configuración.

### Arreglado
- Se corrige una redundancia en `composer.json`.

## 0.1 - 2016-02-05
### Añadido
- Se implementa la API de Flow v1.2

[Unreleased]: https://github.com/cokecancino/laravel-flow/compare/1.2.1...HEAD
[1.2.1]: https://github.com/cokecancino/laravel-flow/compare/1.2...1.2.1
[1.2]: https://github.com/cokecancino/laravel-flow/compare/1.1.3...1.2
[1.1.3]: https://github.com/cokecancino/laravel-flow/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/cokecancino/laravel-flow/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/cokecancino/laravel-flow/compare/1.1...1.1.1
[1.1]: https://github.com/cokecancino/laravel-flow/compare/1.0...1.1
[1.0]: https://github.com/cokecancino/laravel-flow/compare/0.1.1...1.0
[0.1.1]: https://github.com/cokecancino/laravel-flow/compare/0.1...0.1.1
