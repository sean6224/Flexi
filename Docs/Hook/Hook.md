# 🧠 Flexi Hook: Modular Hook System with Dependencies & Attributes
Flexi Hook is a flexible, modern hook system for PHP that supports:

- Before & After hooks with priorities
- Hook dependencies and their execution order
- Attribute-based hook declarations (PHP 8+)
- Automatic hook registration from attributes
- Prevention of recursive hook calls

This system is ideal for modular, extensible applications where you want to inject logic before or after core methods in a clean, controllable way.

## How does it work in practice?
- You add hooks — functions that run before or after specific methods.
- Hooks have priorities — higher priority hooks run earlier.
- Hooks can modify data — before the method you can prepare data, after the method you can send notifications or clear cache.
- You use PHP 8+ attributes — mark methods with #[HookBefore] or #[HookAfter] and register them automatically.
- You call the method — the system runs all attached hooks in the right order.

## Typical usage example:
- Before saving data (method save), you perform:
- - user authentication,
- - logging,
- - data validation.
- After saving data, you send notifications and clear cache.

# 🚀 Initialization and automatic hook registration from attributes
In your class constructor, initialize the hook system and automatically register methods marked with hook attributes:
```php
use Flexi\Hook\Traits\HookableTrait;

class UserService
{
    use HookableTrait;

    public function __construct()
    {
        $this->initHookHandler();
        $this->applyHooksFromAttributes($this);
    }
}

```
> What happens?
The system prepares for hook handling and scans the class to attach methods marked with #[HookBefore] and #[HookAfter].

## 🛠️ Defining hooks using methods with attributes and priorities
```php
use Flexi\Hook\Attributes\HookBefore;
use Flexi\Hook\Attributes\HookAfter;

class UserService
{
    // ...

    #[HookBefore(priority: 5)]
    public function authenticate(array $data): array
    {
        echo "[5] Authenticating user\n";
        $data['authenticated'] = true;
        return [$data]; // BEFORE hooks return method arguments as an array
    }

    #[HookBefore(priority: 10)]
    public function logBeforeSave(array $data): array
    {
        echo "[10] Logging before save\n";
        return [$data];
    }

    #[HookAfter(priority: 10)]
    public function invalidateCache($result, array $data)
    {
        echo "[10] Invalidating cache\n";
        return $result; // AFTER hooks return the method result
    }

    #[HookAfter(priority: 30)]
    public function sendNotification($result, array $data)
    {
        echo "[30] Sending notification after save\n";
        return $result;
    }
}
```

# 💾 Method call triggering hooks
Your core method triggers BEFORE and AFTER hooks which execute additional logic and may modify data:
```php
public function save(array $data = []): string
{
    // Run BEFORE hooks, passing data arguments
    $args = $this->runHooksBefore('save', $data);

    // Main save logic
    echo "💾 Saving user data: " . json_encode($args[0]) . "\n";

    $result = "User saved";

    // Run AFTER hooks with the result and original arguments
    return $this->runHooksAfter('save', $result, ...$args);
}
```

## ⚙️ What happens during save() call?
1. The system runs all BEFORE hooks registered for save, in order from highest to lowest priority.
2. Each BEFORE hook can modify the method arguments.
3. Then the main save() logic executes.
4. After that, the AFTER hooks run and can react to the result and original data.
5. The final (possibly modified) result is returned.

## 📊 Summary and typical output
Calling save(['user' => 'Zarin']) outputs:
```php
[10] Logging before save
[5] Authenticating user
💾 Saving user data: {"user":"Zarin","authenticated":true}
[10] Invalidating cache
[30] Sending notification after save
```