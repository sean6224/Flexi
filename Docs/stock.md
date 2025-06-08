# Flexi Stock: Dynamic Method Overriding at Runtime
The **`StockOverride`** mechanism allows managing methods marked with the Stock attribute. With it, you can:
- Register methods under a specific name,
- Dynamically call these methods by name,
- Override their implementation during runtime,
- Save the overrides to disk.

## Method Registration
Mark a method with the **`#[Stock('nazwaMetody')]`** attribute to register it in the system:
```php
use Flexi\Stock\Core\Stock;
use Flexi\Stock\Core\StockOverride;

class TestStock extends StockOverride
{
    #[Stock('likeYou')]
    public function likeYou(string $name): string {
        return "I like you, $name!";
    }
}
```

## Calling Methods
Call registered methods via the **`call`** method:
```php
$stockHandler = new TestStock();
echo $stockHandler->call('likeYou', 'Jane');
```

## Overriding Methods
You can override a method’s behavior without modifying the original class:
```php
$stockHandler->overrideMethod('likeYou', function ($name) {
    return "I don't  love you, $name!";
}, false);

echo $stockHandler->call('likeYou', 'Tom'); // I don't love you, Tom!
```

## Methods with Multiple Parameters
Supports any number of parameters and types:
```php
$stockHandler->overrideMethod('greet', function(string $p1, string $p2, string $p3) {
    return "$p1 and $p2 are great, but $p3 is special!";
}, false);

echo $stockHandler->call('greet', 'Alice', 'Bob', 'Charlie');
```

## Saving Method Overrides
### Single Override (immediate save to file)
```php
$stockHandler->overrideMethod('likeYou', function ($name) {
    return "I'm sorry, no love you $name!";
}, true);
```

### Save All Overrides at Once
```php
$stockHandler->overrideMethod('likeYou', function ($name) {
    return "I appreciate you, $name!";
});

$stockHandler->overrideMethod('goodbye', function ($name) {
    return "Goodbye, $name!";
});

$stockHandler->saveOverrides();
```

## Summary
- **`StockOverride`** is a powerful tool for dynamic method control in PHP,
- You register methods via the Stock attribute,
- You can call, override, and save changes without editing original classes,
- Perfect for plugin systems, rule engines, and dynamic business logic.