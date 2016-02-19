# Bunyan Formatter for Monolog [![Build Status](https://travis-ci.org/Lunatic666/monolog-bunyan-formatter.svg?branch=master)](https://travis-ci.org/Lunatic666/monolog-bunyan-formatter)

## About

The Bunyan formatter was ported from the [Punyan](https://github.com/zalora/punyan) project to support projects
which already started with Monolog

## Requirements

* >= PHP 5.3
* Composer

## Installation

`composer require lunatic/monolog-bunyan-formatter`

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
