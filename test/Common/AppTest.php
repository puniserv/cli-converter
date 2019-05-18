<?php
declare(strict_types=1);

namespace test\Common;

use AdamDmitruczukRekrutacjaHRTec\Common\App;
use AdamDmitruczukRekrutacjaHRTec\Common\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class AppTest extends TestCase
{
    public function testCorrectAppRun(): void
    {
        $symfonyAppMock = $this->createMock(Application::class);
        $managerMock = $this->createMock(Manager::class);
        $commandMock1 = $this->createMock(Command::class);
        $commandMock2 = $this->createMock(Command::class);
        $app = new App($symfonyAppMock, $managerMock);
        $app->commands = [
            $commandMock1,
            $commandMock2,
        ];
        $symfonyAppMock->expects($this->atLeastOnce())
            ->method('add')
            ->with($commandMock1);
        $symfonyAppMock->expects($this->atLeastOnce())
            ->method('add')
            ->with($commandMock2);
        $symfonyAppMock->expects($this->once())
            ->method('run');
        $managerMock->expects($this->once())->method(
            'endWithSuccess' /**  @uses Manager::endWithSuccess()*/
        );
        $app->run();
    }

    public function testFailedAppRun(): void
    {
        $symfonyAppMock = $this->createMock(Application::class);
        $managerMock = $this->createMock(Manager::class);
        $symfonyAppMock->method('run')->willReturnCallback(function(){
           throw new \Exception('test error');
        });
        $app = new App($symfonyAppMock, $managerMock);
        $managerMock->expects($this->once())->method(
            'endWithError' /**  @uses Manager::endWithError()*/
        );
        $this->expectOutputString('test error');
        $app->run();
    }
}
