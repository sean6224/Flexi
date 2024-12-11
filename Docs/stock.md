## Stock: Overriding methods in classes

This mechanism allows dynamic management of methods in the application, which have been marked with a special Stock attribute. It allows methods to be registered, called and overridden at runtime in the application. This approach provides flexibility and full control over program logic, allowing the application's behavior to be changed without modifying the original method code.

## Method registration
How to register methods?
To register a method in StockHandler, you need to tag it with the Stock attribute. This attribute assigns a name to the method, which will be used later to call it.

Example:
```php
use Flexi\Attributes\Stock;
use Flexi\Stock\StockHandler;

class TestStock extends StockHandler
{
    #[Stock('likeYou')]
    public function likeYou(string $name): string {
        return "I like you, $name!";
    }
}
```
In the above example, the likeYou method is registered under the name 'likeYou' in the StockHandler system.

## Calling methods
To call a registered method, use the call method from the StockHandler class, passing it the method name and optional arguments.

Example:
```php
$stockHandler = new TestStock();
echo $stockHandler->call('likeYou', 'Jane');
```

## Overriding methods
StockHandler allows you to override registered methods using the overrideMethod method. This allows you to change the logic of a method on the fly, without having to change the code of the class itself.

Example:
```php
$stockHandler->overrideMethod('likeYou', function ($name) {
    return "I No love you, $name!";
});

echo $stockHandler->call('likeYou', 'Tom'); // Outputs: I No love you, Tom!
```

When the overrideMethod is called, the likeYou method is overwritten, and its new logic is called each time it is called again.

## Summary
StockHandler is a powerful tool for dynamically managing methods in PHP applications. With Stock attributes, you can easily register methods, call them based on their names, and override their logic on the fly. Combined with overrideMethod, StockHandler allows full control over application logic.