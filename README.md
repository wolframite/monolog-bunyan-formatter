# Bunyan Formatter for Monolog

## About

The Bunyan formatter was ported from the [Punyan](https://github.com/zalora/punyan) project to support projects
which already started with Monolog

## Requirements

* >= PHP 5.3
* Composer

## Installation

## Usage

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Lunatic666\Monolog\Formatter\BunyanFormatter;

$log = new Logger('demo');
$handler = new StreamHandler('php://stdout', Logger::INFO);
$handler->setFormatter(new BunyanFormatter());
$log->pushHandler($handler);

$log->info('Hello, Mr. Bunyan', array('link' => 'https://en.wikipedia.org/wiki/Paul_Bunyan'));
```

## 