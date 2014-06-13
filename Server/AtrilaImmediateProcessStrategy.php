<?php

namespace Ibrows\DeployBundle\Server;

use Ssh\Authentication\PublicKeyFile;
use Ssh\Configuration;
use Ssh\Session;
use Ssh\SshConfigFileConfiguration;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

class AtrilaImmediateProcessStrategy implements ImmediateProcessStrategyInterface
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @param string $host
     */
    public function __construct($host = 'web02.ibrows.cust.atrila.net')
    {
        $this->host = $host;
    }

    /**
     * Starting the deploy process on a server
     * @param string $env
     * @param array $options
     * @param OutputInterface $output
     * @param HelperSet $helperSet
     * @throws \RuntimeException
     */
    public function execute($env, array $options, OutputInterface $output, HelperSet $helperSet)
    {
        $options = array_merge(array(
            'host' => $this->host,
            'port' => 22,
            'config' => null,
            'passphrase' => null,
            'publicKeyFile' => '~/.ssh/id_rsa.pub',
            'privateKeyFile' => '~/.ssh/id_rsa'
        ), $options);

        if(!isset($options['user'])){
            throw new \RuntimeException("Need project user to deploy for atrila");
        }

        /** @var DialogHelper $dialog */
        $dialog = $helperSet->get('dialog');

        $passphrase = $options['passphrase'] == 'ASK' ? $dialog->ask($output, '<question>Passphrase?</question> ') : null;

        if($options['config']){
            $configuration = new SshConfigFileConfiguration($options['config'], $options['host'], $options['port']);
            $authentication = $configuration->getAuthentication($passphrase, $options['user']);
        }else{
            $configuration = new Configuration($options['host'], $options['port']);
            $authentication = new PublicKeyFile($options['user'], $options['publicKeyFile'], $options['privateKeyFile'], $passphrase);
        }

        $session = new Session($configuration, $authentication);
        $exec = $session->getExec();

        $output->writeln('Executing now ibrows_deploy - this process can take several minutes');
        $output->writeln($exec->run('ibrows_deploy -y '. escapeshellarg($env)));
    }
}