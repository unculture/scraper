#Scraper
Scrapes the Sainsbury's grocery site - Ripe Fruits page and returns a JSON array
of all the products on the page. The URL for the page is currently hard coded into the application.

##Installation
Requires PHP 5.4 and up. Install dependencies via Composer.

```
composer install
```


##Usage
```
php scrape.php
```

##Help
To display the help text and list any options / flags:

```
php scrape.php --help
```

##Tests
There are unit tests using PHPUnit. To run them from the project root directory use:

```
./vendor/bin/phpunit
```

