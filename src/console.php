<?php
declare(strict_types=1);

use AdamDmitruczukRekrutacjaHRTec\Common\Container;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../autoload.php')) {
    require __DIR__ . '/../../../autoload.php';
} else {
    die('File autoload.php not exists');
}

(new Container())
    ->init(__DIR__ . '/Resources/services.php')
    ->getApp()
    ->run();
