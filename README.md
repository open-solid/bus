# Message Bus Component

A message bus component is essential in modern applications for managing communication between 
different parts of the system. It acts as a central hub that routes messages between services, 
ensuring decoupled and scalable architecture. This allows individual components to interact without 
needing to know the specifics of each other, simplifying development and maintenance.

## Installation

```bash
composer require open-solid/bus
```

## Usage

### Dispatching Message with the Bus

Think of the "bus" as a mail delivery system for your messages. It follows a 
specific path, decided by some rules (middleware), to send your message and 
handle it.

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

A "message handler" is what does the work when a message arrives. It can be a simple 
function or a method in a class. Here's how you set one up:

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

Middleware are helpers that perform tasks before and after your message is handled. They 
operate at the bus level, meaning they handle all messages dispatched through the message 
bus they are linked to.

Here's how to create one:

```php
use OpenSolid\Bus\Envelope\Envelope;
use OpenSolid\Bus\Middleware\Middleware;
use OpenSolid\Bus\Middleware\NextMiddleware;

class MyMiddleware implements Middleware
{
    public function handle(Envelope $envelope, NextMiddleware $next): void
    {
        // Do something before the message handler works.

        $next->handle($envelope); // Call the next middleware

        // Do something after the message handler is done.
    }
}
```

### Decorators

Decorators are helpers that perform tasks before and after your message is handled. Unlike
Middleware, decorators operate at the handler level, allowing you to modify or enhance specific 
handlers without changing their actual code.

Check this out in [decorator](https://github.com/yceruto/decorator) and [decorator-bundle](https://github.com/yceruto/decorator-bundle) packages. 

## Framework Integration

 * [cqs-bundle](https://github.com/open-solid/cqs-bundle) - Symfony bundle for Command-Query buses.
 * [domain-event-bundle](https://github.com/open-solid/domain-event-bundle) - Symfony bundle for Event bus.

## License

This tool is available under the [MIT License](LICENSE), which means you can use it pretty freely in your projects.
