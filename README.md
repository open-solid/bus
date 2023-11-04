# Simple Messenger Component

## Installation

```bash
composer require yceruto/messenger
```

## Usage

```php
use App\Message\CreateProduct;
use Yceruto\Messenger\Bus\MessageBusFactory;

$createProductHandler = function (CreateProduct $message): mixed {
    // ...
};

$bus = MessageBusFactory::fromHandlers([
    CreateProduct::class => $createProductHandler,
]);

$bus->dispatch(new CreateProduct());
```

## License

This software is published under the [MIT License](LICENSE)
