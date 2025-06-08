# 🧠 Flexi Dynamic: Dynamically Adding Methods to Objects
Flexi\Dynamic allows you to add methods dynamically to objects at runtime. This makes it possible to flexibly extend class behavior without modifying or subclassing them — ideal for plugins, testing, or modular architectures.

## ✅ Registering Methods at Runtime
To start using dynamic methods, include the DynamicMethodAwareTrait in your class and use the registerMethod function to attach a method.

### 🔧 Example Usage
```php
use Flexi\Dynamic\Traits\DynamicMethodAwareTrait;

class MagicService
{
    use DynamicMethodAwareTrait;

    public function __construct()
    {
        $this->registerMethod(
            'greet',
            fn(string $name): string => "Hello, {$name}",
            ['description' => 'Greets the user'],
            'string',
            ['string']
        );
    }
}

$service = new MagicService();
echo $service->greet('Zarin'); // Hello, Zarin
```

### 🧩 What's happening here?
> The greet method is registered at runtime using registerMethod.
> 
> Thanks to the __call magic method, you can invoke the dynamic method like any regular class method.

# 🧪 Type & Metadata Support
Each method can optionally include:
- Return type (returnType)
- Parameter types (parameterTypes)
- Metadata like a description or tags
```php
$service->registerMethod(
    'sum',
    fn(int $a, int $b): int => $a + $b,
    ['description' => 'Adds two numbers'],
    'int',
    ['int', 'int']
);
```

# 📋 Accessing Method Info
You can list all dynamic methods or fetch specific metadata:
```php
$methods = $service->getMethods();
$meta = $service->getMethodMetadata('greet');

print_r($methods); // ['greet', 'sum']
print_r($meta);    // ['description' => 'Greets the user']
```

# 🏗️ Using in Real Classes
The trait integrates seamlessly into existing classes:
```php
class ReportGenerator
{
    use DynamicMethodAwareTrait;

    public function baseReport(): string
    {
        return "Base Report";
    }
}

$report = new ReportGenerator();

$report->registerMethod('extendedReport', function () {
    return "Extended Report with Data";
});

echo $report->baseReport();       // Base Report
echo $report->extendedReport();   // Extended Report with Data
```

## 💡 Use Cases
>-  Plugin systems: Dynamically add behaviors based on runtime conditions or configuration.
>
>- Testing: Inject mock methods directly into objects.
>
>- Modular architecture: Register features or commands at runtime via configs (JSON, YAML, DB).
>
>- Prototyping: Quickly experiment with object behavior without touching core logic.

# ⚠️ Notes & Considerations
>There is a small performance overhead when using dynamic methods.
>
>Type validation is manual — PHP does not enforce types at runtime; NewMethodRegistry can optionally implement custom checks.
>
>Use this flexibility wisely — dynamic behavior is powerful but can lead to harder-to-trace bugs if overused.
