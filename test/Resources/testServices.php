<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use test\Resources\TestApp;

return function(ContainerConfigurator $configurator){
    $configurator->services()
        ->set('App')
        ->class(TestApp::class);
};
