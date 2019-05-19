<?php
declare(strict_types=1);

use AdamDmitruczukRekrutacjaHRTec\Common\Container;

if (file_exists($fromSrcPath = __DIR__ . '/../vendor/autoload.php')) {
    require $fromSrcPath;
} elseif (file_exists($fromVendorBinPath = __DIR__ . '/../../../autoload.php')) {
    require $fromVendorBinPath;
} else {
    die('File autoload.php not exists');
}

(new Container())
    ->init(__DIR__ . '/Resources/services.php')
    ->getApp()
    ->run();
