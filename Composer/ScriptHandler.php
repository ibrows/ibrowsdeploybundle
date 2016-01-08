<?php

namespace Ibrows\DeployBundle\Composer;

use Composer\Script\CommandEvent;

class ScriptHandler extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler
{
    /**
     * Clears the Symfony cache.
     *
     * @param $event CommandEvent A instance
     */
    public static function deploy(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $binDir = $options['symfony-bin-dir'];

        if(!is_dir($binDir)){
            echo 'The symfony-bin-dir ('. $binDir .') specified in composer.json was not found in '. getcwd() .', can not deploy.'.PHP_EOL;
            return;
        }

        static::executeCommand($event, $binDir, 'ibrows:deploy:install', $options['process-timeout']);
    }
}