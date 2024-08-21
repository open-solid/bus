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
Middleware, decorators operate at the handler level, allowing you to modify or enhance the 
handler behavior without changing their actual code.

Essentially, a decorator is a callable that takes another callable as an argument and extends 
or alters its behavior. Let's see an example:

```php
use OpenSolid\Bus\Decorator\Decorator;

class StopwatchDecorator implements Decorator
{
    public function decorate(\Closure $func): \Closure
    {
        return function (mixed ...$args) use ($func): mixed {
            // do something before

            $result = $func(...$args);

            // do something after

            return $result;
        };
    }
} 
```

Then, use it wherever you want to decorate a message handler operation with 
the `#[Decorate]` attribute, which configures the decorator that will wrap 
the current `MyMessageHandler::__invoke()` method.

```php
use App\Decorator\StopwatchDecorator;
use OpenSolid\Bus\Decorator\Decorate;

class MyMessageHandler
{
    #[Decorate(StopwatchDecorator::class)]
    public function __invoke(MyMessage $message): void
    {
        // ...
    }
}
```

If it's a frequently used decorator, you can create a pre-defined class 
to avoid configuring the same decorator repeatedly:

```php
use App\Decorator\StopwatchDecorator;
use OpenSolid\Bus\Decorator\Decorate;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
readonly class Stopwatch extends Decorate
{
    public function __construct()
    {
        parent::__construct(StopwatchDecorator::class);
    }
}
```

Then, you can simply reference your `Stopwatch` attribute decorator as follows:

```php
use App\Decorator\Stopwatch;

class MyMessageHandler
{
    #[Stopwatch]
    public function __invoke(MyMessage $message): void
    {
        // ...
    }
}
```

## Framework Integration

 * [cqs-bundle](https://github.com/open-solid/cqs-bundle) - Symfony bundle for Command-Query buses.
 * [domain-event-bundle](https://github.com/open-solid/domain-event-bundle) - Symfony bundle for Event bus.

## License

This tool is available under the [MIT License](LICENSE), which means you can use it pretty freely in your projects.
