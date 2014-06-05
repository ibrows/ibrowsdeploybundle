<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MysqlDumpCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $gzip = true;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $dateFormat;

    /**
     * @param string $path
     * @param bool $gzip
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $dateFormat
     * @param int $timeout
     */
    public function __construct($path, $gzip = true, $user = 'root', $password = 'root', $name = null, $dateFormat = 'Y_m_d_h_i_s', $timeout = null)
    {
        $this->path = $path;
        $this->gzip = $gzip;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->dateFormat = $dateFormat;
        parent::__construct($timeout);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(parent::getArguments(), array(
            'path' => $this->path,
            'gzip' => $this->gzip,
            'user' => $this->user,
            'password' => $this->password,
            'name' => $this->name,
            'dateFormat' => $this->dateFormat
        ), $args);
    }

    /**
     * @param array $args
     * @throws \RuntimeException
     * @return string
     */
    public function getCommand(array $args)
    {
        if(!$path = $this->getRealPathAndCreate($args['path'])){
            throw new \RuntimeException("Couldn create directory ". $args['path']);
        }

        $file = $path .'/'. $this->getRunServer() .'_'. $this->getRunEnvironment() .'_'. date($args['dateFormat']).'.sql'.($args['gzip'] ? '.gz' : null);

        $mkdirCommand = 'mkdir -p '. escapeshellarg($args['path']);
        $dumpCommand = 'mysqldump -u '. escapeshellarg($args['user']) .' -p'. escapeshellarg($args['password']) .' '. escapeshellarg($args['name']) .''.($args['gzip'] ? ' | gzip' : null).' > '. escapeshellarg($file);

        return $mkdirCommand.' && '. $dumpCommand;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getRealPathAndCreate($path)
    {
        if($path[0] == '~'){
            $path = $this->getHomeDirectory() . substr($path, 1);
        }

        if(!is_dir($path)){
            @mkdir($path, 0777, true);
        }

        return realpath($path);
    }

    /**
     * @return string
     */
    protected function getHomeDirectory()
    {
        return trim(shell_exec('cd ~ && pwd'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mysqldump';
    }
}