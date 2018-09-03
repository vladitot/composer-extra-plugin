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
        $settings = $this->getAllExtra('composer.json', $input->getArgument('param'));
        foreach ($settings as $key=>$prop) {
            $output->write($key.'='.$prop.''." ");
        }
        $output->writeln('');
    }

    /**
     * Getter params from extra
     * Say them, which key do you want to get. Another time you will able to export them, for example.
     * @param $file
     * @param $searchForString
     * @return array
     */
    protected function getAllExtra($file, $searchForString) {
        $searchFor = explode('-', $searchForString);
        $searchable = [];

        $content = json_decode(file_get_contents($file), true);
        if (isset($content['extra'])) {
            $currentEl = $content['extra'];

            foreach ($searchFor as $item) {
                if (isset($currentEl[$item])) {
                    $currentEl = $currentEl[$item];
                } else {
                    break;
                }
            }
            if ($currentEl != $content['extra']) {
                $searchable = $currentEl;
            }
        }

        if (isset($content['extra']['merge-plugin']['include'])) {
            $extraIncludes = $content['extra']['merge-plugin']['include'];
            $includedFoundSearchables = [];
            foreach ($extraIncludes as $file) {
                $includedFoundSearchables = array_replace($includedFoundSearchables, $this->getAllExtra($file, $searchForString));
            }
        } else {
            $includedFoundSearchables = [];
        }
        return array_replace($searchable, $includedFoundSearchables);
    }
}