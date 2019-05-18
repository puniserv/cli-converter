<?php
declare(strict_types=1);

namespace test\Content\Provider;

use AdamDmitruczukRekrutacjaHRTec\Content\Provider\HttpXmlProvider;
use AdamDmitruczukRekrutacjaHRTec\Exception\ContentParseError;
use AdamDmitruczukRekrutacjaHRTec\Exception\MissingParameter;
use AdamDmitruczukRekrutacjaHRTec\Exception\ServerConnectionFail;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HttpXmlProviderTest extends TestCase
{
    public function testWithMissingPath(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $provider = new HttpXmlProvider($guzzleMock);
        $this->expectException(MissingParameter::class);
        $this->expectExceptionMessage("Parameter 'path' is missing");
        $provider->get();
    }

    public function testWithErrorResponsePath(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $responseMock = $this->createMock(Response::class);
        $provider = new HttpXmlProvider($guzzleMock);
        $path = 'somePath';
        $provider->setHttpPath($path);
        $expectedPath = "http://$path";
        $this->mockGetInGuzzle($guzzleMock, $expectedPath, $responseMock);
        $this->expectGuzzleGet($guzzleMock, $expectedPath);
        $responseMock->method('getStatusCode')->willReturn(403);
        $this->expectException(ServerConnectionFail::class);
        $this->expectExceptionMessage('Server connection fail. Code: "403", Message: "Invalid status code!"');
        $provider->get();
    }

    public function testWithExceptionResponsePath(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $provider = new HttpXmlProvider($guzzleMock);
        $path = 'http://somePath';
        $provider->setHttpPath($path);
        $guzzleMock->method('__call')
            ->with('get', [$path])
            ->willReturnCallback(function () {
                throw new \Exception('test!');
            });
        $guzzleMock->expects($this->once())->method('__call')->with('get', [$path]);
        $this->expectException(ServerConnectionFail::class);
        $this->expectExceptionMessage('Server connection fail. Code: "0", Message: "test!"');
        $provider->get();
    }

    public function testWithSuccessRequestButInvalidBody(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $responseMock = $this->createMock(Response::class);
        $provider = new HttpXmlProvider($guzzleMock);
        $path = 'somePath';
        $provider->setHttpPath($path);
        $expectedPath = "http://$path";
        $this->mockGetInGuzzle($guzzleMock, $expectedPath, $responseMock);
        $this->expectGuzzleGet($guzzleMock, $expectedPath);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn('some invalid xml');
        $this->expectException(ContentParseError::class);
        $this->expectExceptionMessage('During the parsing, the following errors appeared: Start tag expected, \'<\' not found');
        $provider->get();
    }

    public function testWithSuccessRequestAndCorrectBody(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $responseMock = $this->createMock(Response::class);
        $provider = new HttpXmlProvider($guzzleMock);
        $path = 'somePath';
        $provider->setHttpPath($path);
        $expectedPath = "http://$path";
        $this->mockGetInGuzzle($guzzleMock, $expectedPath, $responseMock);
        $this->expectGuzzleGet($guzzleMock, $expectedPath);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn('<test>123</test>');
        $this->assertSame(
            "<?xml version=\"1.0\"?>\n<test>123</test>\n",
            $provider->get()->getRawStringValue()
        );
    }

    private function mockGetInGuzzle(MockObject $guzzleMock, string $expectedPath, $responseMock): void
    {
        $guzzleMock->method('__call')->with('get', [$expectedPath])->willReturn($responseMock);
    }

    private function expectGuzzleGet(MockObject $guzzleMock, string $expectedPath): void
    {
        $guzzleMock->expects($this->once())->method('__call')->with('get', [$expectedPath]);
    }
}
