<?php
/**
 * Created by PhpStorm.
 * User: vladitot
 * Date: 29.08.18
 * Time: 12:00
 */

namespace ExtraPlugin;


use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunScriptCommand extends BaseCommand
{
    /**
     * First-time set up
     */
    protected function configure()
    {
        $this->setDescription('Can be used to to start scripts via tty');
        $this->setName('runt');
        $this->addArgument('param', InputArgument::REQUIRED, 'Name of script which need to run from "extra -> extra-commands" block');
        $this->addArgument('options', InputArgument::IS_ARRAY, "additional parameters");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mainComposerFile = 'composer.json';
        $baseDir = dirname(realpath($mainComposerFile));
        $commands = StaticHelper::getAllExtra($mainComposerFile, 'extracommands', $baseDir);
        if (!isset($commands[$input->getArgument('param')])) {
            echo $input->getArgument('param').' - not found in extra -> extra-commands block';
            exit(1);
        }
        $process = new \Symfony\Component\Process\Process(
            $commands[$input->getArgument('param')].' '.implode(' ', $input->getArgument('options'))
        );
        $process->setTty(true);
        $process->setPty(true);
        $process->run();
    }


}