StockHandler to klasa odpowiedzialna za zarządzanie metodami w aplikacji, które są oznaczone specjalnym atrybutem Stock. Umożliwia to rejestrowanie, wywoływanie, a także nadpisywanie metod w locie, zapewniając elastyczność i kontrolę nad logiką aplikacji. Dzięki refleksji w PHP, StockHandler może dynamicznie rejestrować metody i pozwalać na ich późniejsze wywoływanie lub nadpisywanie bez konieczności modyfikacji samego kodu metod.

## Rejestrowanie metod
Jak rejestrować metody?
Aby zarejestrować metodę w systemie StockHandler, należy ją oznaczyć atrybutem Stock. Atrybut ten przypisuje nazwę metody, która będzie używana później do jej wywoływania.

Przykład:
```php
use Nex\System\Attributes\Stock;

class TestStock extends StockHandler
{
    #[Stock('likeYou')]
    public function likeYou(string $name): string {
        return "I like you, $name!";
    }
}
```
W powyższym przykładzie, metoda likeYou jest rejestrowana pod nazwą 'likeYou' w systemie StockHandler.

## Wywoływanie metod
Aby wywołać zarejestrowaną metodę, należy użyć metody call z klasy StockHandler, przekazując jej nazwę metody oraz opcjonalne argumenty.

Przykład:
```php
$stockHandler = new TestStock();
echo $stockHandler->call('likeYou', 'Jane');
```

## Nadpisywanie metod
StockHandler umożliwia nadpisywanie zarejestrowanych metod za pomocą metody overrideMethod. Dzięki temu można zmienić logikę danej metody w locie, bez konieczności zmiany kodu samej klasy.

Przykład:
```php
$stockHandler->overrideMethod('likeYou', function ($name) {
    return "I No love you, $name!";
});

echo $stockHandler->call('likeYou', 'Tom'); // Outputs: I No love you, Tom!
```

Po wywołaniu metody overrideMethod, metoda likeYou zostaje nadpisana, a jej nowa logika jest wywoływana przy każdym kolejnym wywołaniu.

## Podsumowanie
StockHandler to potężne narzędzie do dynamicznego zarządzania metodami w aplikacjach PHP. Dzięki atrybutom Stock programiści mogą w prosty sposób rejestrować metody, wywoływać je na podstawie nazw, a także nadpisywać ich logikę w locie. W połączeniu z metodą overrideMethod, StockHandler pozwala na pełną kontrolę nad logiką aplikacji.