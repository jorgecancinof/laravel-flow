<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la URL de su página de éxito
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | 'http://www.comercio.cl/kpf/exito.php',
    | ['url' => 'flow/exito'],
    | ['route' => 'flow.exito'],
    | ['action' => 'FlowController@exito'],
    |
    */

    'url_exito' => ['route' => 'flow.exito'],

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la URL de su página de fracaso
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | 'http://www.comercio.cl/kpf/fracaso.php',
    | ['url' => 'flow/fracaso'],
    | ['route' => 'flow.fracaso'],
    | ['action' => 'FlowController@fracaso'],
    |
    */

    'url_fracaso' => ['route' => 'flow.fracaso'],

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la URL de su página de confirmación
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | 'http://www.comercio.cl/kpf/confirmacion.php',
    | ['url' => 'flow/confirmacion'],
    | ['route' => 'flow.confirmacion'],
    | ['action' => 'FlowController@confirmacion'],
    |
    */

    'url_confirmacion' => ['route' => 'flow.confirmacion'],

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

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la página de pago de Flow
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    | Sitio de pruebas = http://flow.tuxpan.com/app/kpf/pago.php
    | Sitio de producción = https://www.flow.cl/app/kpf/pago.php
    |
    */

    'url_pago' => env('FLOW_URL_PAGO', 'http://flow.tuxpan.com/app/kpf/pago.php'),

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la ruta (path) de su sitio en donde estará la llave privada
    |--------------------------------------------------------------------------
    */

    'keys' => base_path('keys'),

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la ruta (path) de su sitio en donde estarán los archivos de logs
    |--------------------------------------------------------------------------
    */

    'logPath' => storage_path('logs'),

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí el email con el que está registrado en Flow
    |--------------------------------------------------------------------------
    */

    'comercio' => env('FLOW_COMERCIO', 'emailFlow@comercio.com'),

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí el medio de pago
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | Solo Webpay = 1
    | Solo Servipag = 2
    | Solo Multicaja = 3
    | Todos los medios de pago = 9
    |
    */

    'medioPago' => '9',

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí el modo de acceso
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | Mostrar pasarela Flow = f
    | Ingresar directamente al medio de pago = d
    |
    */

    'tipo_integracion' => 'f',

];
