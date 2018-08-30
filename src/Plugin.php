<?php
/**
 * Created by PhpStorm.
 * User: vladitot
 * Date: 29.08.18
 * Time: 11:53
 */

namespace ExtraPlugin;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;


class Plugin implements PluginInterface, Capable
{
    public function activate(Composer $composer, IOInterface $io)
    {

    }

    public function getCapabilities()
    {
        return array(
            'Composer\Plugin\Capability\CommandProvider' => 'ExtraPlugin\CommandProvider',
        );
    }
}