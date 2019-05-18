<?php
declare(strict_types=1);

namespace test\Command;

use AdamDmitruczukRekrutacjaHRTec\Command\Csv;
use AdamDmitruczukRekrutacjaHRTec\Content\Provider\HttpXmlProvider;
use AdamDmitruczukRekrutacjaHRTec\Converters\SimpleXmlToCsv;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsvTest extends TestCase
{
    public function testCommand(): void
    {
        $command = new Csv(
            'csv:test',
            'some test',
            $this->createMock(SimpleXmlToCsv::class),
            $http = $this->createMock(HttpXmlProvider::class)
        );
        $this->assertSame('csv:test', $command->getName());
        $this->assertSame('some test', $command->getDescription());
        $result = $command->run(
            $this->createMock(InputInterface::class),
            $this->createMock(OutputInterface::class)
        );
        $this->assertSame(1, $result);
    }
}
