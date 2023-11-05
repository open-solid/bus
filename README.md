# Simple Messenger Component

## Installation

```bash
composer require yceruto/messenger
```

## Usage

### Bus

The bus sends messages and its actions are guided by a set sequence of middleware.

```php
use App\Message\MyMessage;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersLocator;
use Yceruto\Messenger\Middleware\HandlerMiddleware;

$handler = function (MyMessage $message): mixed {
    // Message processing...
};

$bus = new NativeMessageBus([
    new HandlerMiddleware(new HandlersLocator([
        CreateProduct::class => [$handler],
    ])),
]);

$bus->dispatch(new MyMessage());
```

### Handler

After being sent to the bus, messages are processed by a "message handler". This handler, a PHP callable such as a 
function or a class instance, performs the necessary actions for your message.

```php
use App\Message\MyMessage;

class MyMessageHandler
{
    public function __invoke(MyMessage $message): mixed
    {
        // Message processing...
    }
}
```

### Middleware

Middleware is a piece of code that is executed before and after the message handler. It can modify the message or
perform any other logic.

```php
use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Messenger\Model\Envelope;

class MyMiddleware implements Middleware
{
    public function handle(Envelope $envelope, callable $next): void
    {
        // Before message handler...

        $next($envelope);

        // After message handler...
    }
}
```

## License

This software is published under the [MIT License](LICENSE)
