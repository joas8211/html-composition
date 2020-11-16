# HTML Composition
HTML builder for PHP

## Features
* Method chaining :link:
* Optional pretty printing :heart_eyes:
* Element or complete document rendering :art:
* Configurable indentation :pencil:
* Code injection :syringe:

## Installation

### With Composer
Install HTML Composition to your project with Composer:
```shell
composer require joas8211/html-composition
```

And require autoload.php if you haven't already:
```php
require __DIR__ . '/vendor/autoload.php';
```

### Without Composer
1. Download HtmlComposition.php from releases.
2. Require the file in the PHP file where you need it.
```php
require 'HtmlComposition.php';
```

## Usage
Example usage:
```php
use HtmlComposition\HtmlComposition;

echo (new HtmlComposition)
    ->document()
    ->tag('html', ['lang' => 'en'])
        ->tag('head')
            ->tag('title')->text('Example Document')->end()
        ->end()
        ->tag('body')
            ->tag('h1')->text('Hello World!')->end()
            ->tag('img', [
                'src' => 'https://picsum.photos/768/432',
                'alt' => '',
            ], true)
        ->end()
    ->end();
```

Above code generates following HTML:
```html
<!doctype html>
<html lang="en">
    <head>
        <title>
            Example Document
        </title>
    </head>
    <body>
        <h1>
            Hello World!
        </h1>
        <img src="https://picsum.photos/768/432" alt="">
    </body>
</html>
```
