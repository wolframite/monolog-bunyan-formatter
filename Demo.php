<?php

require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Lunatic\Monolog\Formatter\BunyanFormatter;

$log = new Logger('demo');
$handler = new StreamHandler('php://stdout', Logger::INFO);
$handler->setFormatter(new BunyanFormatter());
$log->pushHandler($handler);

$log->info('Hello, Mr. Bunyan', array('link' => 'https://en.wikipedia.org/wiki/Paul_Bunyan'));
