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
    const CSV_EXTENSION = 'csv';
    public $extend = false;
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
        try {
            $content = $this->getData($input->getArgument(self::URL_PATH));
            $savePath = $this->fixCsvPath($input->getArgument(self::SAVE_PATH));
            if ($this->extend) {
                $this->converter->setContentToExtend($savePath);
            }
            $newContent = $this->converter->convert($content);
            $newContent->saveToFile($savePath);
        } catch (\Throwable $throwable) {
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

    protected function fixCsvPath(string $path): string
    {
        if (pathinfo($path)['extension'] ?? null !== self::CSV_EXTENSION) {
            $path = $path . '.' . self::CSV_EXTENSION;
        }
        return $path;
    }
}
