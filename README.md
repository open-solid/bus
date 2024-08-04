# Simple Bus Component

## Installation

To add the package to your project, open your terminal and type in:

```bash
composer require open-solid/bus
```

## Usage

### Dispatching Message with the Bus

Think of the "bus" as a mail delivery system for your messages. It follows a specific path, decided 
by some rules (middleware), to send your message and handle it.

Here's a snippet on how to set it up and dispatch a message:

```php
use App\Message\MyMessage;
use OpenSolid\Bus\Handler\MessageHandlersLocator;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\NativeMessageBus;

// This is your custom function that does something when a message arrives.
$handler = function (MyMessage $message): mixed {
    // Do stuff with the message here...
};

// Setting up the bus with a middleware that knows who handles the message.
$bus = new NativeMessageBus([
    new HandlingMiddleware(new MessageHandlersLocator([
        MyMessage::class => [$handler], // Maps messages to handlers.
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

### Middleware

Middleware are helpers that do stuff before and after your message is handled. They can change the message or do other tasks.

Here’s how to create one:

```php
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NextMiddleware;

class MyMiddleware implements Middleware
{
    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        // Do something before the message handler works.

        $next->handle($envelope); // Pass the message to the next middleware

        // Do something after the message handler is done.
    }
}
```

## Framework Integration

 * [cqs-bundle](https://github.com/open-solid/cqs-bundle) - Symfony bundle for Command-Query buses.
 * [domain-event-bundle](https://github.com/open-solid/domain-event-bundle) - Symfony bundle for Event bus.

## License

This tool is available under the [MIT License](LICENSE), which means you can use it pretty freely in your projects.
