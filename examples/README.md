# Smaple

Copy directory `AMQP` (this repo) to `App\Http\Controllers` (laravel/lumen application) then use it!

## publish message

Register following route in laravel application

```shell
Route::get('/amqp/publish', [\App\Http\Controllers\AMQP\GetStarted::class, 'publish']);
```

Register following route in lumen application

```shell
$router->get('/amqp/publish', \App\Http\Controllers\AMQP\GetStarted::class . '@publish');
```

Finally, you can publish the message by sending a curl request to the path `/amqp/publish`

## consume message

Just need to exec following command

```shell
php artisan consume
```
