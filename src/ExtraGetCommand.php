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

class ExtraGetCommand extends BaseCommand
{
    /**
     * First-time set up
     */
    protected function configure()
    {
        $this->setDescription('Can be used to get paramaters from "extra" block of main and merged composer.json files');
        $this->setName('extra-get');
        $this->addArgument('param', InputArgument::REQUIRED, 'Name of parameter which need to get from "extra" block');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mainComposerFile = 'composer.json';
        $baseDir = dirname(realpath($mainComposerFile));
        $settings = StaticHelper::getAllExtra('composer.json', $input->getArgument('param'), $baseDir);
        $summary = '';
        foreach ($settings as $key=>$prop) {
            $summary.=($key.'='.$prop.''." ");
        }
        $output->writeln(trim($summary));
    }


}