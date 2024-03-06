# Simple Messenger

## Setting It Up

First things first, you need to add Simple Messenger to your project. Open your terminal and type in:

```bash
composer require open-solid/messenger
```

## How to Use It

### Sending Messages with the Bus

Think of the "bus" as a mail delivery system for your messages. It follows a specific path, decided by some rules (middleware), to send your messages.

Here's a snippet on how to set it up and send a message:

```php
use App\Message\MyMessage;
use OpenSolid\Messenger\Bus\NativeMessageBus;
use OpenSolid\Messenger\Handler\HandlersLocator;
use OpenSolid\Messenger\Middleware\HandlerMiddleware;

// This is your custom function that does something when a message arrives.
$handler = function (MyMessage $message): mixed {
    // Do stuff with the message here...
};

// Setting up the bus with a middleware that knows who handles the message.
$bus = new NativeMessageBus([
    new HandlerMiddleware(new HandlersLocator([
        MyMessage::class => [$handler], // Match messages to handlers.
    ])),
]);

// Send a message using the bus.
$bus->dispatch(new MyMessage());
```

### Handling Messages

A "message handler" is what does the work when a message arrives. It can be a simple function or a method in a class. 
Here's how you set one up:

```php
use App\Message\MyMessage;

class MyMessageHandler
{
    public function __invoke(MyMessage $message): mixed
    {
        // Process the message here...
    }
}
```

### Middleware Magic

Middleware are helpers that do stuff before and after your message is handled. They can change the message or do other tasks.

Here’s how to create one:

```php
use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Messenger\Model\Envelope;

class MyMiddleware implements Middleware
{
    public function handle(Envelope $envelope, callable $next): void
    {
        // Do something before the message handler works.

        $next($envelope); // Pass the message along.

        // Do something after the message handler is done.
    }
}
```

## Integration with other Frameworks

 * [cqs-bundle](https://github.com/open-solid/cqs-bundle) - Symfony bundle for using Simple Messenger with the Command-Query Separation pattern.

## License Info

This tool is available under the [MIT License](LICENSE), which means you can use it pretty freely in your projects.
