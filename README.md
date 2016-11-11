# Laravel Flow

Laravel package para la integración de pagos con [Flow](https://www.flow.cl)

## Instalación

### Paso 1: Instalar a través de Composer

```sh
$ composer require cokecancino/laravel-flow
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

### Paso 4: Publicar el archivo de configuración

```sh
$ php artisan vendor:publish --provider="CokeCancino\LaravelFlow\FlowServiceProvider"
```

### Paso 5: Configura tu `.env` o modifica tu `config/flow.php`

```
…
FLOW_URL_PAGO=http://flow.tuxpan.com/app/kpf/pago.php
FLOW_COMERCIO=emailFlow@comercio.com
```

## Utilización

Este package actúa prácticamente como un simple Service Provider para el [Kit de Integración de Flow](https://www.flow.cl/apiFlow.php), por lo tanto, me limitaré a ejemplificar solo las diferencias de su utilización dentro de Laravel.

**Importante:** [Excluye la protección CSRF](https://laravel.com/docs/master/routing#csrf-excluding-uris) para las páginas de éxito, fracaso y confirmación, ya que provocan excepciones al comunicarse con Flow.

### Creando una Nueva Orden

Controller: `app\Http\Controllers\FlowController.php`

```php
<?php

namespace App\Http\Controllers;

use Flow;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FlowController extends Controller
{
    public function orden(Request $request)
    {
        $orden = [
            'orden_compra'  => '1000',
            'monto'         => 20000,
            'concepto'      => 'Pago de Orden N° 1000',
            'email_pagador' => 'usuario@email.com',

            // Opcional: Medio de Pago (Webpay = 1, Servipag = 2, Ambos = 9)
            //'medio_pago'    => $request->input('medio_pago'),
        ];

        // Genera una nueva Orden de Pago, Flow la firma y retorna un paquete de datos firmados
        $orden['flow_pack'] = Flow::new_order($orden['orden_compra'], $orden['monto'], $orden['concepto'], $orden['email_pagador']);
        
        // Si desea enviar el medio de pago usar la siguiente línea
        //$orden['flow_pack'] = Flow::new_order($orden['orden_compra'], $orden['monto'], $orden['concepto'], $orden['email_pagador'], $orden['medio_pago']);

        return view('flow.orden', $orden);
    }
…
```

---

View: `resources\views\flow\orden.blade.php`

```php
@extends('layouts._master')

@section('content')
    <!-- Formulario HTML que envía la Nueva Orden -->
    <form method="POST" action="{{ config('flow.url_pago') }}" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="parameters" value="{{ $flow_pack }}">
        <button type="submit">Pagar en Flow</button>
    </form>
@endsection
```

### Página de Confirmación

Controller: `app\Http\Controllers\FlowController.php`

```php
…
public function confirmacion()
{
    try {
        // Lee los datos enviados por Flow
        Flow::read_confirm();
    } catch (Exception $e) {
        // Si hay un error responde false
        echo Flow::build_response(false);
        return;
    }

    // Recupera los valores de la Orden
    $FLOW_STATUS  = Flow::getStatus();      // El resultado de la transacción (EXITO o FRACASO)
    $ORDEN_NUMERO = Flow::getOrderNumber(); // N° Orden del Comercio
    $MONTO        = Flow::getAmount();      // Monto de la transacción
    $ORDEN_FLOW   = Flow::getFlowNumber();  // Si $FLOW_STATUS = "EXITO" el N° de Orden de Flow
    $PAGADOR      = Flow::getPayer();       // El email del pagador

    /**
     * Aquí puede validar la Orden
     * Si acepta la Orden responder Flow::build_response(true)
     * Si rechaza la Orden responder Flow::build_response(false)
     */
    if ($FLOW_STATUS == 'EXITO') {
        // La transacción fue aceptada por Flow
        // Aquí puede actualizar su información con los datos recibidos por Flow
        echo Flow::build_response(true); // Comercio acepta la transacción
    } else {
        echo Flow::build_response(false); // Comercio rechaza la transacción
    }
}
…
```

### Página de Éxito

Controller: `app\Http\Controllers\FlowController.php`

```php
…
public function exito()
{
    /**
     * Página de éxito del Comercio
     * Esta página será invocada por Flow cuando la transacción resulte exitosa
     * y el usuario presione el botón para retornar al comercio desde Flow
     */

    // Lee los datos enviados por Flow
    Flow::read_result();

    // Recupera los datos enviados por Flow
    $orden = [
        'orden_compra'  => Flow::getOrderNumber(),
        'monto'         => Flow::getAmount(),
        'concepto'      => Flow::getConcept(),
        'email_pagador' => Flow::getPayer(),
        'flow_orden'    => Flow::getFlowNumber(),
    ];

    return view('flow.exito', $orden);
}
…
```

---

View: `resources\views\flow\exito.blade.php`

```php
@extends('layouts._master')

@section('content')
    <h1>Página de éxito de Comercio</h1>
    Su pago se ha realizado exitosamente<br><br>
    Orden de Compra: {{ $orden_compra }}<br>
    Monto: {{ $monto }}<br>
    Descripción: {{ $concepto }}<br>
    Pagador: {{ $email_pagador }}<br>
    Flow Orden N°: {{ $flow_orden }}<br>
    <br>
    Gracias por su compra
@endsection
```

### Página de Fracaso

Controller: `app\Http\Controllers\FlowController.php`

```php
…
public function fracaso()
{
    /**
     * Página de fracaso del Comercio
     * Esta página será invocada por Flow cuando la transacción no se logre pagar
     * y el usuario presione el botón para retornar al comercio desde Flow
     */

    // Lee los datos enviados por Flow
    Flow::read_result();

    // Recupera los datos enviados por Flow
    $orden = [
        'orden_compra' => Flow::getOrderNumber(),
        'monto'        => Flow::getAmount(),
        'concepto'     => Flow::getConcept(),
    ];

    return view('flow.fracaso', $orden);
}
…
```

---

View: `resources\views\flow\fracaso.blade.php`

```php
@extends('layouts._master')

@section('content')
    <h1>Página de fracaso de Comercio</h1>
    Su pago ha sido rechazado<br><br>
    Orden de Compra: {{ $orden_compra }}<br>
    Monto: {{ $monto }}<br>
    Descripción: {{ $concepto }}<br>
    <br>
    Si lo desea intente nuevamente
@endsection
```
