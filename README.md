# ATOM Feed to CSV converter

Converter use symfony DI, symfony console application and GuzzleHTTP.  
Feed month in pubDate column will be translated by system to locale set in converter configuration.  
 

### How to use (standalone)

Transform feed http link into csv file and overwrite existing file

```sh
$ php src/console.php csv:simple SOURCE_URL PATH_TO_SAVE
````
Transform feed http link into csv file and extend existing file
```sh
$ php src/console.php csv:extended SOURCE_URL PATH_TO_SAVE
```

### Installation
PHP7.1 Required with libxml, simplexml, json extensions

```sh
$ git clone https://github.com/puniserv/cli-converter
$ composer install
```

### Available test commands in composer
```sh
$ composer phpunit
$ composer test
$ composer testCoverageWithHtml
$ composer testCoverage
```
