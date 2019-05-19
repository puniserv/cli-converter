<?php
declare(strict_types=1);

use AdamDmitruczukRekrutacjaHRTec\Common\Container;

require __DIR__ . '/../vendor/autoload.php';

(new Container())
    ->init(__DIR__ . '/Resources/services.php')
    ->getApp()
    ->run();
