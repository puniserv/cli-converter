<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Common;

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
        try{
            foreach($this->commands as $command){
                $this->application->add($command);
            }
            $this->application->run();
            $this->manager->endWithSuccess();
        }catch (\Throwable $throwable){
            echo $throwable->getMessage();
            $this->manager->endWithError();
        }
    }

    private function loadConfig(): void
    {
        if(isset($this->config['timezone'])){
            date_default_timezone_set($this->config['timezone']);
        }
        if(isset($this->config['locale'])){
            setlocale(LC_TIME, $this->config['locale']);
        }
    }
}
