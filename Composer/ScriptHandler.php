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
        $appDir = $options['symfony-app-dir'];

        if(!is_dir($appDir)){
            echo 'The symfony-app-dir ('. $appDir .') specified in composer.json was not found in '. getcwd() .', can not deploy.'.PHP_EOL;
            return;
        }

        static::executeCommand($event, $appDir, 'ibrows:deploy:run', $options['process-timeout']);
    }
}