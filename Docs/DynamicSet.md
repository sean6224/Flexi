# 🧩 Flexi\DynamicSet: Dynamic Data Models with Fluent Setters, Validation & More
Flexi\DynamicSet is for creating flexible data models that support:
- Dynamic fluent setters (set_property(...))
- Automatic validation based on rules
- Object-to-array serialization
- Runtime method registration via __call

>Perfect for building powerful DTOs, configuration objects, form models, or complex data structures.

## ⚙️ Quick Example
```php
$customer = Customer::new()
    ->set_name('Michael Smith')
    ->set_address(
        Address::new()
            ->set_city('San Francisco')
            ->set_postalCode('94105')
    );

$customer->validate();

print_r($customer->toArray());
```

## 🔧 Fluent Setters
By extending DynamicObject, your classes automatically gain dynamic fluent setters:
```php
class Product extends DynamicObject
{
    public string $name;
    public float $price;
}
```
Now you can do:

```php
$product = Product::new()
->set_name('Shoes')
->set_price(149.99);
```
>Under the hood, set_price(149.99) sets $this→price dynamically, with validation and lifecycle hooks.

## ✅ Validation Built In
Define validation rules per property using built-in rules like:
- RequiredRule
- MinLengthRule
- EmailRule
- RangeRule

```php
protected function rules(): array
{
    return [
        'name' => [new RequiredRule()],
        'email' => [new EmailRule()],
    ];
}
```
Trigger validation:
```php
$object->validate(); // throws ValidationException on failure
```
>Nested objects (like Address inside Customer) are validated recursively.

## 📤 Serialization & Deserialization
Convert entire object trees to arrays and back:
```php
$array = $customer->toArray();
$copy = Customer::fromArray($array);
```
>This includes nested objects!

## 🪝 Lifecycle Hooks
Optionally override these methods to intercept object behavior:

| Hook                                | Purpose                               |
|-------------------------------------|---------------------------------------|
| beforeSet, afterSet                 | Modify or respond to property changes |
| beforeSerialize, afterSerialize     | Customize array output                |
| beforeValidate, afterValidate       | Hook into validation                  |
| beforeDeserialize, afterDeserialize | Control loading behavior              |

## 🧪 Example: Customer + Address
```php
class Address extends DynamicObject
{
    public string $city;
    public string $postalCode;

    protected function rules(): array
    {
        return [
            'city' => [new RequiredRule()],
            'postalCode' => [new RequiredRule()],
        ];
    }
}

class Customer extends DynamicObject
{
    public string $name;
    public Address $address;

    protected function rules(): array
    {
        return [
            'name' => [new RequiredRule()],
            'address' => [new RequiredRule()],
        ];
    }
}
```
