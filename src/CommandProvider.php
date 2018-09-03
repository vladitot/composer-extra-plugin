<?php
/**
 * Created by PhpStorm.
 * User: vladitot
 * Date: 29.08.18
 * Time: 12:20
 */

namespace ExtraPlugin;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands()
    {
        return array(new ExtraGetCommand(), new RunScriptCommand());
    }
}