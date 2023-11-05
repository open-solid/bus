# Simple Messenger Component

## Installation

```bash
composer require yceruto/messenger
```

## Usage

```php
use App\Message\CreateProduct;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandlerMiddleware;

$handler = function (CreateProduct $message): mixed {
    // ...
};

$bus = new NativeMessageBus([
    new HandlerMiddleware(new HandlersLocator([
        CreateProduct::class => [$handler],
    ])),
]);

$bus->dispatch(new CreateProduct());
```

## License

This software is published under the [MIT License](LICENSE)
