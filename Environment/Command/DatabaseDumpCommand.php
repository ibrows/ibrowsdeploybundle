<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class DatabaseDumpCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $outputFile;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $outputFile
     * @param string $database
     * @param string $username
     * @param string $password
     * @param int $timeout
     * @param string $phpExecutablePath
     */
    public function __construct($outputFile, $database, $username = 'root', $password = 'root', $timeout = null, $phpExecutablePath = null)
    {
        $this->outputFile = $outputFile;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        parent::__construct($timeout, $phpExecutablePath);
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return 'mysqldump -u '. $this->username .' -p'. $this->password .' '. $this->database .' > '. $this->outputFile;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mysqldump';
    }
}