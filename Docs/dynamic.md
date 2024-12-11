## Dynamic: Dynamic addition of methods and properties.
Dynamic allows us to create objects that can have methods and properties dynamically added as the application runs. This allows us to flexibly extend the functionality of objects according to current needs.


## Adding methods
To start using Dynamic, you need to add a method to an object using the addMethod function. The example below shows how to register methods:
```php
use Flexi\Dynamic\DynamicClass;

$dynamicObject = new DynamicClass();
$username = 'user';
$password = 'usessds';

$dynamicObject->addMethod('validateUserData', function (string $username, string $password): bool {
    return !empty($username) && strlen($password) >= 8;
});

if (!$dynamicObject->validateUserData($username, $password)) {
    echo 'Invalid username or password';
    return;
}

```
Example of the result of above code:
```php
Invalid username or password
```

In this example:
- We create a **`DynamicClass`** object.
- We add a method **`validateUserData`** that verifies the user data.
- We call the dynamically added method on the object.

# Adding methods to classes
**`DynamicClass`** allows you to extend methods in inheriting classes. You can add new methods to classes inheriting from **`DynamicClass`** as if they were part of this class.

Example in class:
```php
use Flexi\Dynamic\DynamicClass;

class UserDynamic extends DynamicClass
{
    public function hello()
    {
        return "Hello, world!";
    }
}

$user = new UserDynamic();
$user->addMethod('sayGoodbye', function () {
    return "Goodbye!";
});

echo $user->hello();       // Outputs: Hello, world!
echo $user->sayGoodbye();  // Outputs: Goodbye!
```

In this example:
- The **`UserDynamic`** class inherits from **`DynamicClass`** and has a built-in **`hello`** method.
- We dynamically add the **`sayGoodbye`** method and call both methods.

## Dynamic properties
**`DynamicClass`** also allows you to add properties to objects as the application runs. Properties are managed using the magic methods **`__get`**, **`__set`**, **`__isset`** and **`__unset`**.

## Example of dynamic properties
```php
$user = new UserDynamic();
$user->username = 'john_doe';  // dynamic property

echo $user->username; // Outputs: john_doe
```

In this example:
- We add the dynamic property username to the **`UserDynamic`** object.
This property can be accessed via magic methods.

## Application
- Extending application functionality: Adding new methods and properties as the application runs, allowing great flexibility in customizing object logic.

- Creating flexible objects: Objects that can change their behavior at execution time, ideal for dynamic environments.

- Testing and modularity: Allows you to create more flexible classes that can be easily modified during testing or as the application runs.

# ⚠️ Important
- There is some performance cost to using **`DynamicClass** because objects must support dynamic methods and properties.
- Keep in mind that dynamic addition of methods and properties should be used only when the specifics of the application require it.