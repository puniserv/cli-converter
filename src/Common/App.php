<?php
declare(strict_types=1);

namespace Src\Common;

use Src\Exception\WarningException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class App
{
    /** @var array */
    public $config;
    /** @var Command[] */
    public $commands = [];
    /** @var Application */
    private $application;
    /** @var Manager */
    private $manager;

    public function __construct(Application $application, Manager $manager)
    {
        $this->application = $application;
        $this->manager = $manager;
    }

    public function run(): void
    {
        $this->loadConfig();
        $this->setErrorHandler();
        try {
            foreach ($this->commands as $command) {
                $this->application->add($command);
            }
            $this->application->run();
            $this->manager->endWithSuccess();
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage();
            $this->manager->endWithError();
        }
    }

    private function loadConfig(): void
    {
        $timezone = $this->config['timezone'] ?? null;
        if ($timezone !== null) {
            date_default_timezone_set($timezone);
            ini_set('date.timezone', $timezone);
        }
        if (isset($this->config['locale'])) {
            setlocale(LC_ALL, $this->config['locale']);
        }
    }

    private function setErrorHandler(): void
    {
        set_error_handler(static function ($error, $message, $file, $line) {
            throw new WarningException($message, 0, $error, $file, $line);
        }, E_WARNING);
    }
}
