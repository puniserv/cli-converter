<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Common;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class Container
{
    /*** @var \ProjectServiceContainer */
    private $container;

    public function init(string $configPath): self
    {
        $hash = md5(file_get_contents($configPath));
        $file = __DIR__ . "/../../runtime/container-$hash.php";
        if (!file_exists($file)) {
            $containerBuilder = new ContainerBuilder();
            $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
            $loader->load($configPath);
            $containerBuilder->compile();
            $dumper = new PhpDumper($containerBuilder);
            file_put_contents($file, $dumper->dump());
        }
        require_once $file;
        $this->container = new \ProjectServiceContainer();
        return $this;
    }

    public function getApp(): App
    {
        return $this->container->get('App');
    }
}
