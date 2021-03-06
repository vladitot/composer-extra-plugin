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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunScriptCommand extends BaseCommand
{
    /**
     * First-time set up
     */
    protected function configure()
    {
        $this->setDescription('Can be used to to start scripts via tty');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->setName('wrunt');
        } else {
            $this->setName('runt');
        }
        $this->addArgument('commandName', InputArgument::REQUIRED, 'Name of script which need to run from "extra -> extra-commands" block');
        $this->addOption('no-interactive', null, InputOption::VALUE_OPTIONAL, 'Don`t enable tty', false);
        $this->addArgument('options', InputArgument::IS_ARRAY, "additional parameters");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $additionalRegular = '/\@\d+%/';
        if (preg_match($additionalRegular, $input->getArgument('commandName'))) {
            echo 'You cant start additional commands only';
            exit(1);
        }
        $mainComposerFile = 'composer.json';
        $baseDir = dirname(realpath($mainComposerFile));
        $commands = StaticHelper::getAllExtra($mainComposerFile, 'extracommands', $baseDir);
        if (!isset($commands[$input->getArgument('commandName')])) {
            echo $input->getArgument('commandName').' - not found in extra -> extra-commands block';
            exit(1);
        }

        $matches=[];
        preg_match_all($additionalRegular, $commands[$input->getArgument('commandName')], $matches);

        $command = str_replace('@params%', implode(' ', $input->getArgument("options")), $commands[$input->getArgument('commandName')]);

        if (count($matches[0])>0) {
            foreach ($matches[0] as $addCommand) {
                $command = str_replace($addCommand, $commands[$addCommand], $command);
            }
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo $command;
        } else {
            $process = new \Symfony\Component\Process\Process(
                $command
            );
            if (!$input->getOption('no-interactive')) {
                $process->setTty(true);
            } else {
                $process->setTty(false);
            }
            $process->run();
        }
    }


}