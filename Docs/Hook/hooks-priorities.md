## Hook Priorities

The hook system allows you to inject additional logic into your application processes, allowing you to flexibly manage the order in which actions are performed. Priorities allow you to specify precisely when a particular hook should be called in relation to others. The lower the priority, the later the hook will be called within the same category (e.g. HookBefore or HookAfter).

## Hook Priorities
**`HookBefore`**.
Hooks registered with **`HookBefore`** are called before the main action. The priorities in this case work in a way where the hook with a lower priority (i.e. a higher number) will be called later in the sequence.

Example:
```php
$hookHandler->addHookBefore('beforeAction', function () {
    echo "Hook: Before action executed (priority: 10).\n";
}, 10);

$hookHandler->addHookBefore('beforeAction', function () {
    echo "Hook: Before action executed (priority: 5).\n";
}, 5);
```
Order of call:
- Hook with priority 10.
- Hook with priority 5

**`HookAfter`** Hooks registered with **`HookAfter`** are called after the main action. As with HookBefore, hooks with lower priority will be called later.

Example:
```php
$hookHandler->addHookAfter('afterAction', function ($result) {
    echo "Hook: After action executed (priority: 10). Result: $result\n";
    return $result;
}, 10);

$hookHandler->addHookAfter('afterAction', function ($result) {
    echo "Hook: After action executed (priority: 5). Result: $result\n";
    return $result;
}, 5);
```
Order of call:
- Hook with priority 10.
- Hook with priority 5

## How does the priority work in practice?
**`HookBefore`**:
- Hook HookBefore with priority 10 will be called before the hook with priority 5.
- In case you have more than one hook before an action, their priority determines the order of calling.

**`HookAfter`**:
- Hook HookAfter with priority 10 will be called before the hook with priority 5.
- With priorities, you have full control over when and what operations will be performed after the main action.

## Example of using hooks with priorities
```php
use Nex\System\Hook\HookHandler;

$hookHandler = new HookHandler();

$hookHandler->addHookBefore('beforeAction', function () {
    echo "Hook: Before action executed (priority: 5).\n";
}, 5);

$hookHandler->addHookBefore('beforeAction', function () {
    echo "Hook: Before action executed (priority: 10).\n";
}, 10);

$hookHandler->addHookAfter('afterAction', function ($result) {
    echo "Hook: After action executed (priority: 5). Result: $result\n";
    return $result;
}, 5);

$hookHandler->addHookAfter('afterAction', function ($result) {
    echo "Hook: After action executed (priority: 10). Result: $result\n";
    return $result;
}, 10)

$result = $hookHandler->runHooksAfter('mainAction');
echo "Final result: $result\n";
```
Output:
```php
Hook: Before action executed (priority: 10).
Hook: Before action executed (priority: 5).
Hook: After action executed (priority: 10). Result: MainResult
Hook: After action executed (priority: 5). Result: MainResult
Final result: MainResult
```