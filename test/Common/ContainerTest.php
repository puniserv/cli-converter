<?php
declare(strict_types=1);

namespace test\Common;

use AdamDmitruczukRekrutacjaHRTec\Common\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use test\Resources\TestApp;

class ContainerTest extends TestCase
{
    private const TEST_CONFIG = __DIR__ . '/../Resources/testServices.php';

    public function testCompiler(): void
    {
        $this->clearOldFiles();
        $cacheContainerPath = $this->getCacheContainerPath();
        $this->assertFileNotExists($cacheContainerPath);
        $instance = new Container();
        $instance->init(self::TEST_CONFIG);
        $this->assertFileExists($cacheContainerPath);
        $this->assertInstanceOf(TestApp::class, $instance->getApp());
        $instance = new Container();
        $instance->init(self::TEST_CONFIG);
    }

    public function testRealConfig(): void
    {
        $config = require __DIR__ . '/../../src/Resources/services.php';
        $containerBuilder = new ContainerBuilder();
        $x = [];
        $containerConf = new ContainerConfigurator(
            $containerBuilder,
            new PhpFileLoader($containerBuilder, $this->createMock(FileLocatorInterface::class)),
            $x,
            __DIR__,
            'someFile'
        );
        $this->assertIsCallable($config);
        $this->assertNull($config($containerConf));
    }

    private function clearOldFiles(): void
    {
        $path = $this->getCacheContainerPath();
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function getCacheContainerPath(): string
    {
        $hash = md5(file_get_contents(self::TEST_CONFIG));
        return __DIR__ . "/../../runtime/container-$hash.php";
    }
}