# 🧠 Flexi Hook Dependency: Practical Hook Execution Order with Dependencies
Flexi\Hook allows you to define dependencies between hooks, so you can precisely control the order in which they execute.
This example shows how to create a hook chain (validate, sanitize → save) that runs in a specified order with additional functions before and after.

## 🚀 Initialization and Defining Dependencies
In the class constructor, initialize the hook system and declare that the save hook depends on the validating and sanitizing hooks.
This means calling save will first run validate and sanitize hooks in order:

```php
use Flexi\Hook\Traits\HookDependencyTrait;

class MyService
{
    use HookTrait;
    public function __construct()
    {
        $this->initHookDependency();
        $this->addHookDependency('save', ['validate', 'sanitize']);
    }
}
```

## 🛠️ Adding Hooks Before and After
Hooks can be attached before method execution (before hooks) and after (after hooks). In this example:
- addHookBefore('validate', ...) — runs just before validate.
- addHookBefore('sanitize', ...) — runs just before sanitize.
- addHookBefore('save', ...) — runs just before save.
- addHookAfter('save', ...) — runs right after save, e.g. confirmations or notifications.

### Adding hooks before method execution
```php
// In constructor or any other method

$this->addHookBefore('validate', function($data) {
    echo "🔍 Checking data...\n";
    return $data;
});

$this->addHookBefore('sanitize', function($data) {
    echo "🧹 Cleaning data...\n";
    if (is_string($data)) {
        $data = trim($data);
    }
    return $data;
});

$this->addHookBefore('save', function($data) {
    echo "💾 Preparing to save...\n";
    return $data;
});
```
> What happens?
Before hooks run right before the actual method, allowing data preparation or modification.

### Adding hooks after method execution
```php
$this->addHookAfter('save', function($result) {
    echo "✅ Save completed successfully.\n";
    return $result;
});

$this->addHookAfter('save', function($result) {
    echo "📬 Sending notification...\n";
    return $result;
});
```
> What happens?
After hooks run after the method, perfect for notifications, logging, or further processing.
### Class methods triggering hooks with dependencies
```php
public function validate($data)
{
    echo "✔️ Validating data\n";
    return $data;
}

public function sanitize($data)
{
    echo "✔️ Sanitizing data\n";
    if (is_string($data)) {
        return trim($data);
    }
    return $data;
}

public function save($data = null)
{
    // Run before hooks respecting dependencies
    $args = $this->runHooksBeforeWithDependencies('save', $data);

    $result = "Saved: " . implode(', ', $args);

    // Run after hooks with the same context
    return $this->runHooksAfterWithDependencies('save', $result, ...$args);
}
```

## Resulting output:
```🔍 Data Verification...
🔍 Checking data...
✔️ Validating data
🧹 Cleaning data...
✔️ Sanitizing data
💾 Preparing to save...
✅ Save completed successfully.
📬 Sending notification...
```

## 🧩 What’s happening here?
- Calling save() triggers runHooksBeforeWithDependencies('save', $data) which first runs validate and sanitize with their before hooks.
- Then the main save logic runs.
- Finally, the after hooks for save run (e.g. confirmations, notifications).
- Each hook can modify and return data to be passed along.

## 💡 Use Cases
- Control execution order for validation, processing, and saving data.
- Extend processes without modifying original methods.
- Add logic layers like cleaning, validation, and notifications modularly.
- Avoid code duplication by linking hooks with dependencies.

# ⚠️ Important Notes
- Define dependencies carefully as they determine execution order.
- Cyclic dependencies throw exceptions — Flexi detects such errors.
- Hooks should return data to maintain data flow continuity.