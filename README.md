# Laravel Flow Package

A Laravel Package to use [Flow](https://www.flow.cl)

## Installation

### Step 1: Install through Composer

```
composer require cokecancino/laravel-flow
```

### Step 2: Add the Service Provider

In the `app/config/app.php` file, add the following to the `providers`  array:
```php
'providers' => array(
    …
    CokeCancino\LaravelFlow\FlowServiceProvider::class,
    …
),
```

### Step 3: Add the alias

In the `app/config/app.php` file, add the following to the `aliases`  array:
```php
'aliases' => array(
    …
    'Flow' => CokeCancino\LaravelFlow\Facades\Flow::class,
    …
),
```

### Step 3: Publish the configuration


```sh
$ php artisan vendor:publish --provider="CokeCancino\LaravelFlow\FlowServiceProvider"
```

### Step 4: Configure your .env or edit your brand new `config/flow.php`
```
...
FLOW_URL_PAGO=http://flow.tuxpan.com/app/kpf/pago.php
FLOW_COMERCIO=emailFlow@comercio.com
```
