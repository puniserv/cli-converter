# ATOM Feed to CSV converter

Converter use symfony DI, symfony console application and GuzzleHTTP.  
Feed month in pubDate column will be translated by system to locale set in converter configuration.  
 

### How to use

Transform feed http link into csv file and overwrite existing file
> php bin/console.php csv:simple SOURCE_URL PATH_TO_SAVE

Transform feed http link into csv file and extend existing file
> php bin/console.php csv:extended SOURCE_URL PATH_TO_SAVE

### Installation
PHP7.1 Required with libxml, simplexml, json extensions

```sh
$ composer require puniserv/cli-converter
```

### Available test commands in composer
```sh
$ composer phpunit
$ composer test
$ composer testCoverageWithHtml
$ composer testCoverage
```
