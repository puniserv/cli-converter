<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Command;

use AdamDmitruczukRekrutacjaHRTec\Content\Provider\HttpXmlProvider;
use AdamDmitruczukRekrutacjaHRTec\Content\XmlContent;
use AdamDmitruczukRekrutacjaHRTec\Converters\SimpleXmlToCsv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Csv extends Command
{
    private const URL_PATH = 'URL_PATH';
    private const SAVE_PATH = 'SAVE_PATH';
    /** @var SimpleXmlToCsv */
    private $converter;
    /** @var HttpXmlProvider */
    private $httpXmlProvider;

    public function __construct(string $name, string $description, SimpleXmlToCsv $converter, HttpXmlProvider $httpXmlProvider)
    {
        parent::__construct($name);
        $this->setDescription($description);
        $this->converter = $converter;
        $this->httpXmlProvider = $httpXmlProvider;
    }

    protected function configure(): void
    {
        $this->addArgument(
            self::URL_PATH,
            InputArgument::REQUIRED,
            'Source FEED http url'
        );
        $this->addArgument(
            self::SAVE_PATH,
            InputArgument::REQUIRED,
            'Destination absolute or relative file path'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try{
            $content = $this->getData($input->getArgument(self::URL_PATH));
            $newContent = $this->converter->convert($content);
            $savePath = $input->getArgument(self::SAVE_PATH);
            $newContent->saveToFile($savePath);
        }catch(\Throwable $throwable){
            $output->write('Error: ' . $throwable->getMessage(), true);
            return 1;
        }
        $output->write("File saved in '$savePath'", true);
        return 0;
    }

    protected function getData(string $path): XmlContent
    {
        return $this->httpXmlProvider->setHttpPath($path)->get();
    }
}
