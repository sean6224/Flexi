## Configuring Hooks

The hook system allows us to inject additional logic into application processes, so we can control when and how various actions will be performed. Below you will find a description of the configuration and practical examples.

## Registering Hooks using HookHandler.
To start using the hook system, you need to use the HookHandler class, which allows you to register functions called before (HookBefore) and after (HookAfter) the main actions.
Below is an example of registering hooks:

```php
use Nex\System\Hook\HookHandler;

$hookHandler = new HookHandler();

$hookHandler->addHookBefore('beforeAction', function () {
    echo "Hook: Before action executed\n";
});

$hookHandler->addAction('mainAction', function () {
    echo "Executing main action.\n";
    return "MainResult";
});

$hookHandler->addHookAfter('afterAction', function ($result) {
    echo "Hook: After action executed (priority: 5). Result: $result\n";
    return $result;
});

$result = $hookHandler->execute('mainAction');
echo "Final result: $result\n";
```

Example of the result of above code:
```php
Hook: Before action executed.
Executing main action.
Hook: After action executed Result: MainResult
Final result: MainResult
```

## Differences between Attributes and Manual Registration.
Attributes **`HookBefore`** and **`HookAfter`** allow you to mark class methods as hooks:

```php
use Nex\System\Hook\Hook;
use Nex\System\Attributes\Hook\HookBefore;
use Nex\System\Attributes\Hook\HookAfter;

class UserHook extends Hook
{
    #[HookBefore(priority: 10)]
    public function beforeAction() {
        echo "Hook: Before action executed.\n";
    }

    #[HookAfter(priority: 5)]
    public function afterAction($result) {
        echo "Hook: After action executed. Result: $result\n";
        return $result;
    }
}
```

## Disadvantages of Attributes
An attribute-based system can be less flexible for dynamic requirements, such as:

- Adding hooks while the application is running.
- Modifying priorities without changing the class code.
- Registering multiple functions for the same event.

## Recommendations
- For dynamic applications: Use HookHandler to manage hooks manually, which provides full control and flexibility.
- For static applications: Use HookBefore and HookAfter attributes if you prefer a more declarative approach.

## ⚠️ Important
- Priorities are not supported in this version of the hook system. Priority support will be available in a separate document in the future. 
- If you use HookHandler, make sure that the functions are properly registered and supported.


For more information, see document:
### [Hook Priorities](hooks-priorities.md)
---