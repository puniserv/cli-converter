<?php
declare(strict_types=1);

namespace test\Command;

use AdamDmitruczukRekrutacjaHRTec\Command\Csv;
use AdamDmitruczukRekrutacjaHRTec\Content\Provider\HttpXmlProvider;
use AdamDmitruczukRekrutacjaHRTec\Content\XmlContent;
use AdamDmitruczukRekrutacjaHRTec\Converters\SimpleXmlToCsv;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class CsvTest extends TestCase
{
    public function testSuccessCommandWithExtend(): void
    {
        $this->prepareSuccessTest(function (Csv $command, MockObject $converter) {
            $command->extend = true;
            $converter->expects($this->once())->method(
                'setContentToExtend'/** @uses SimpleXmlToCsv::setContentToExtend() */
            )->with('somePath.csv');
        });
    }

    public function testSuccessCommandWithoutExtend(): void
    {
        $this->prepareSuccessTest(function (Csv $command, MockObject $converter) {
            $command->extend = false;
            $converter->expects($this->never())->method(
                'setContentToExtend'/** @uses SimpleXmlToCsv::setContentToExtend() */
            );
        });
    }

    public function testCommandWithError(): void
    {
        $command = $this->getCsvCommandInstance(
            $this->createMock(SimpleXmlToCsv::class),
            $this->createMock(HttpXmlProvider::class)
        );
        $this->checkNameAndDescription($command);
        $result = $command->run(
            $this->createMock(InputInterface::class),
            $this->createMock(OutputInterface::class)
        );
        $this->assertSame(1, $result);
    }

    private function prepareSuccessTest(\Closure $closure): void
    {
        $converter = $this->createMock(SimpleXmlToCsv::class);
        $http = $this->createMock(HttpXmlProvider::class);
        $command = $this->getCsvCommandInstance($converter, $http);
        $this->checkNameAndDescription($command);
        $input = new StringInput('someWebSite.local somePath');
        $output = $this->createMock(OutputInterface::class);
        $contentMock = $this->createMock(XmlContent::class);
        $converter->expects($this->once())->method(
            'convert'/** @uses SimpleXmlToCsv::convert() */
        )->with($contentMock);
        $closure($command, $converter);
        $http->method(
            'setHttpPath'/** @uses HttpXmlProvider::setHttpPath() */
        )->willReturn($http);
        $http->expects($this->once())->method(
            'setHttpPath'/** @uses HttpXmlProvider::setHttpPath() */
        )->with('someWebSite.local');
        $http->method(
            'get'/** @uses HttpXmlProvider::get() */
        )->willReturnCallback(function () use ($contentMock) {
            return $contentMock;
        });
        $http->expects($this->once())->method(
            'get'/** @uses HttpXmlProvider::get() */
        );
        $output->expects($this->once())->method('write')
            ->with("File saved in 'somePath.csv'");
        $result = $command->run($input, $output);
        $this->assertSame(0, $result);
    }

    protected function getCsvCommandInstance($converter, $http): Csv
    {
        return new Csv(
            'csv:test',
            'some test',
            $converter,
            $http
        );
    }

    protected function checkNameAndDescription(Csv $command): void
    {
        $this->assertSame('csv:test', $command->getName());
        $this->assertSame('some test', $command->getDescription());
    }
}
