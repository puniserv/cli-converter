<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Content\Provider;

use AdamDmitruczukRekrutacjaHRTec\Exception\ContentParseError;
use AdamDmitruczukRekrutacjaHRTec\Exception\MissingParameter;
use AdamDmitruczukRekrutacjaHRTec\Content\XmlContent;
use AdamDmitruczukRekrutacjaHRTec\Exception\ServerConnectionFail;
use AdamDmitruczukRekrutacjaHRTec\Parser\Xml;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class HttpXmlProvider
{
    /** @var string */
    private $path;
    /*** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setHttpPath(string $path): self
    {
        if (!preg_match('~^(?:f|ht)tps?://~i', $path)) {
            $path = 'http://' . $path;
        }
        $this->path = $path;
        return $this;
    }

    public function get(): XmlContent
    {
        if (!$this->path) {
            throw new MissingParameter('path');
        }
        $parser = new Xml();
        if (!$parser->parse($this->getXmlFromHttp($this->path))) {
            throw new ContentParseError($parser->getErrors());
        }
        return $parser->getContent();
    }

    /**
     * @param string $path
     * @return string
     * @throws ServerConnectionFail
     */
    protected function getXmlFromHttp(string $path): string
    {
        try {
            $res = $this->client->get($path);
            $code = $res->getStatusCode();
            if ($code === 200) {
                return (string)$res->getBody();
            }
            $message = 'Invalid status code!';
        } catch (\Throwable $throwable) {
            $code = $throwable->getCode();
            $message = $throwable->getMessage();
        }
        throw new ServerConnectionFail($message, $code);
    }
}
