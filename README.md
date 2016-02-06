# Laravel Flow Package

Laravel Package para la integración de pagos con [Flow](https://www.flow.cl)

## Instalación

### Paso 1: Instalar a través de Composer

```
composer require cokecancino/laravel-flow
```

### Paso 2: Agregar el Service Provider

En el archivo `app/config/app.php`, agregar la siguiente línea al array `providers`:
```php
'providers' => array(
    …
    CokeCancino\LaravelFlow\FlowServiceProvider::class,
    …
),
```

### Paso 3: Agregar el alias

En el archivo `app/config/app.php`, agregar la siguiente línea al array `aliases`:
```php
'aliases' => array(
    …
    'Flow' => CokeCancino\LaravelFlow\Facades\Flow::class,
    …
),
```

### Paso 3: Publicar el archivo de configuración


```sh
$ php artisan vendor:publish --provider="CokeCancino\LaravelFlow\FlowServiceProvider"
```

### Paso 4: Configura tu .env o modifica tu `config/flow.php`
```
...
FLOW_URL_PAGO=http://flow.tuxpan.com/app/kpf/pago.php
FLOW_COMERCIO=emailFlow@comercio.com
```
