# Laravel Flow

Laravel package para la integración de pagos con [Flow](https://www.flow.cl)

Testeado con Laravel 5.1 y 5.5

## Instalación

### Paso 1: Instalar a través de Composer

```sh
$ composer require cokecancino/laravel-flow
```

### Paso 2: Agregar el Service Provider

En el archivo `config/app.php`, agregar la siguiente línea al array `providers`:
```php
'providers' => [
    …
    CokeCancino\LaravelFlow\FlowServiceProvider::class,
    …
],
```

### Paso 3: Agregar el alias

En el mismo archivo, agregar la siguiente línea al array `aliases`:
```php
'aliases' => [
    …
    'Flow' => CokeCancino\LaravelFlow\Facades\Flow::class,
    …
],
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

Este package actúa prácticamente como un simple Service Provider para el [Kit de Integración de Flow](https://www.flow.cl/pagos-web-sistema.php), por lo tanto, me limitaré a ejemplificar solo las diferencias de su utilización dentro de Laravel.

**Importante:** [Excluye la protección CSRF](https://laravel.com/docs/master/csrf#csrf-excluding-uris) para las páginas de éxito, fracaso y confirmación, ya que Flow no sabrá qué token CSRF enviar a tus rutas.

---

### Formulario de Compra

View: `resources/views/index.blade.php`

```php
@extends('layouts._master')

@section('content')
    <form method="POST" action="{{ route('orden') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        Orden N°: <input type="text" name="orden" id="orden" placeholder="1000" required><br>
        Monto: <input type="text" name="monto" id="monto" placeholder="20000" required><br>
        Descripción: <input type="text" name="concepto" id="concepto" placeholder="Pago de Orden N° 1000" required><br>
        Email pagador (opcional): <input type="email" name="pagador" id="pagador" placeholder="usuario@email.com"><br>
        <br>
        <button type="submit">Aceptar</button>
    </form>
@endsection
```

---

### Creando una nueva Orden

Controller: `Http/Controllers/FlowController.php`

```php
<?php

namespace App\Http\Controllers;

use Flow;

use Illuminate\Http\Request;

class FlowController extends Controller
{
    /**
     * Creando una nueva Orden
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function orden(Request $request)
    {
        $orden = [
            'orden_compra'  => $request->input('orden'),
            'monto'         => $request->input('monto'),
            'concepto'      => $request->input('concepto'),
            'email_pagador' => $request->input('pagador'),

            // Opcional: Medio de Pago (Webpay = 1, Servipag = 2, Multicaja = 3, Todos = 9)
            //'medio_pago'    => $request->input('medio_pago'),
        ];

        // Genera una nueva Orden de Pago, Flow la firma y retorna un paquete de datos firmados
        $orden['flow_pack'] = Flow::new_order($orden['orden_compra'], $orden['monto'], $orden['concepto'], $orden['email_pagador']);

        // Si desea enviar el medio de pago usar la siguiente línea
        //$orden['flow_pack'] = Flow::new_order($orden['orden_compra'], $orden['monto'], $orden['concepto'], $orden['email_pagador'], $orden['medio_pago']);

        return view('orden', $orden);
    }
…
```

View: `resources/views/orden.blade.php`

```php
@extends('layouts._master')

@section('content')
    <!-- Formulario HTML que envía la nueva Orden -->
    Confirme su orden antes de proceder al pago via Flow<br>
    <br>
    Orden N°: {{ $orden_compra }}<br>
    Monto: {{ $monto }}<br>
    Descripción: {{ $concepto }}<br>
    Email pagador (opcional): {{ $email_pagador }}<br>
    <br>
    <form method="POST" action="{{ config('flow.url_pago') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="parameters" value="{{ $flow_pack }}">
        <button type="submit">Pagar en Flow</button>
    </form>
@endsection
```

---

### Página de Éxito

Controller: `Http/Controllers/FlowController.php`

```php
…
    /**
     * Página de éxito del Comercio
     *
     * Esta página será invocada por Flow cuando la transacción resulte exitosa
     * y el usuario presione el botón para retornar al comercio desde Flow.
     *
     * @return \Illuminate\View\View
     */
    public function exito()
    {
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

View: `resources/views/flow/exito.blade.php`

```php
@extends('layouts._master')

@section('content')
    <h1>Página de éxito de Comercio</h1>
    Su pago se ha realizado exitosamente<br>
    <br>
    Orden de Compra: {{ $orden_compra }}<br>
    Monto: {{ $monto }}<br>
    Descripción: {{ $concepto }}<br>
    Pagador: {{ $email_pagador }}<br>
    Flow Orden N°: {{ $flow_orden }}<br>
    <br>
    Gracias por su compra
@endsection
```

---

### Página de Fracaso

Controller: `Http/Controllers/FlowController.php`

```php
…
    /**
     * Página de fracaso del Comercio
     *
     * Esta página será invocada por Flow cuando la transacción no se logre pagar
     * y el usuario presione el botón para retornar al comercio desde Flow.
     *
     * @return \Illuminate\View\View
     */
    public function fracaso()
    {
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

        return view('flow.fracaso', $orden);
    }
…
```

View: `resources/views/flow/fracaso.blade.php`

```php
@extends('layouts._master')

@section('content')
    <h1>Página de fracaso de Comercio</h1>
    Su pago ha sido rechazado<br>
    <br>
    Orden de Compra: {{ $orden_compra }}<br>
    Monto: {{ $monto }}<br>
    Descripción: {{ $concepto }}<br>
    Pagador: {{ $email_pagador }}<br>
    Flow Orden N°: {{ $flow_orden }}<br>
    <br>
    <a href="{{ url('/') }}">Intente nuevamente</a>
@endsection
```

---

### Página de Confirmación

Controller: `Http/Controllers/FlowController.php`

```php
…
    /**
     * Página de confirmación del Comercio
     *
     * @return void
     */
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
        $flow_status  = Flow::getStatus();      // El resultado de la transacción (EXITO o FRACASO)
        $orden_numero = Flow::getOrderNumber(); // N° de Orden del Comercio
        $monto        = Flow::getAmount();      // Monto de la transacción
        $orden_flow   = Flow::getFlowNumber();  // Si $flow_status = 'EXITO' el N° de Orden de Flow
        $pagador      = Flow::getPayer();       // El email del pagador

        /**
         * Aquí puede validar la Orden
         *
         * Si acepta la Orden responder Flow::build_response(true)
         * Si rechaza la Orden responder Flow::build_response(false)
         */
        if ($flow_status == 'EXITO') {
            // La transacción fue aceptada por Flow
            // Aquí puede actualizar su información con los datos recibidos por Flow
            echo Flow::build_response(true); // Comercio acepta la transacción
        } else {
            // La transacción fue rechazada por Flow
            // Aquí puede actualizar su información con los datos recibidos por Flow
            echo Flow::build_response(false); // Comercio rechaza la transacción
        }
    }
…
```

---

### Routes

```php
…
Route::get('/', function () {
    return view('index');
});
Route::post('orden', 'FlowController@orden')->name('orden');

Route::post('flow/exito', 'FlowController@exito')->name('flow.exito');
Route::post('flow/fracaso', 'FlowController@fracaso')->name('flow.fracaso');
Route::post('flow/confirmacion', 'FlowController@confirmacion')->name('flow.confirmacion');
…
```
