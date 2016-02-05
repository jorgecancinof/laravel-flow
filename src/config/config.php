<?php

/*
 * Página de configuración del comercio
 *
 * Version: 1.2
 * Date:    2015-05-28
 * Author:  flow.cl
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la URL de su página de éxito
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
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
    | ['url' => 'flow/confirmacion'],
    | ['route' => 'flow.confirmacion'],
    | ['action' => 'FlowController@confirmacion'],
    |
    */
    'url_confirmacion' => ['route' => 'flow.confirmacion'],

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la página de pago de Flow
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    | Sitio de pruebas = http://flow.tuxpan.com/app/kpf/pago.php
    | Sitio de produccion = https://www.flow.cl/app/kpf/pago.php
    |
    */
    'url_pago' => env('FLOW_URL_PAGO', 'http://flow.tuxpan.com/app/kpf/pago.php'),

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la ruta (path) en su sitio donde estará la llave privada
    |--------------------------------------------------------------------------
    */
    'keys' => base_path('keys'),

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la ruta (path) en su sitio donde estarán los archivos de logs
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
    | Todos los medios de pago = 9
    |
    */
    'medioPago' => '9',

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí el modo de acceso a Webpay
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | Mostrar pasarela Flow = f
    | Ingresar directamente a Webpay = d
    |
    */
    'tipo_integracion' => 'f',

    /*
    |--------------------------------------------------------------------------
    | Ingrese aquí la tasa de comisión de Flow que usará
    |--------------------------------------------------------------------------
    |
    | Valores posibles:
    | Pago siguiente día hábil = 1 (Expreso)
    | Pago a tres días hábiles = 2 (Veloz)
    | Pago a cinco días hábiles = 3 (Normal)
    |
    */
    'tasa_default' => env('FLOW_TASA_DEFAULT', 3),

];
