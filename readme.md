# Voyager Schema Generator

## Установка
* Выполнить команду `composer require "atwinta/voyager-schema"`
* Опубликовать конфиг и пример схемы `php artisan vendor:publish --provider="Atwinta\Voyager\Providers\SchemaServiceProvider"`
* Для применения данных используйте команду `php artisan voyager:schema`

## Другое
Всё заполнение данных происходит в конфиге `voyager-schema.php`  
Заполнение конфига схем:
```php
    "schemas" => [
        \App\Schema\Table\UserDataType::class
    ],
```

Заполнение конфига меню:
```php
    "menu" => [
        ["class" => DataType::class]
    ]
    ...
    // С детьми
    "menu" => [
        ["class" => DataType::class, "children" => [
            ["class" => DataType::class]        
        ]]
    ]
``` 
***
Класс структуры должен наследовать абстрактный класс `\Atwinta\Voyager\Schema\BaseDataType`  

Вы также можете использовать интефейс `\Atwinta\Voyager\Schema\Abstracts\DataTypeInterface` если вам не нужны методы класса BaseDataType  
