<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class DoctrineSchemaUpdateCommand extends AbstractSymfonyCommand
{
    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'force' => false,
            'dumpSql' => true,
            'complete' => false
        ), $args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function getCommand(array $args)
    {
        $cmd = 'doctrine:schema:update';

        if($args['force']){
            $cmd .= ' --force';
        }

        if($args['dumpSql']){
            $cmd .= ' --dump-sql';
        }

        if($args['complete']){
            $cmd .= ' --complete';
        }

        return $cmd;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'doctrineschemaupdate';
    }
}