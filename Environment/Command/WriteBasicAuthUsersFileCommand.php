<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;

class WriteBasicAuthUsersFileCommand extends AbstractCommand
{
    /**
     * @var array
     */
    protected $users = array();

    /**
     * @var string
     */
    protected $path;

    /**
     * @param array $users
     * @param string $path
     * @param int $timeout
     */
    public function __construct(array $users = array(), $path = '~/.htpasswd', $timeout = 300)
    {
        $this->users = $users;
        $this->path = $path;
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
            'users' => $this->users
        ), $args);
    }

    /**
     * @param array $args
     * @param OutputInterface $output
     * @throws \RuntimeException
     * @return void
     */
    public function run(array $args, OutputInterface $output)
    {
        $args = $this->getArguments($args);

        $content = array();
        foreach($args['users'] as $data){
            $content[] = $data['user'].':'. $this->crypt_apr1_md5($data['pass']);
        }

        $output->writeln('Write '. count($args['users']).' users to '. $args['path']);
        file_put_contents($this->getRealPath($args['path']), implode("\n\r", $content)."\n\r");
    }

    /**
     * @param array $args
     * @throws \RuntimeException
     * @return string
     */
    protected function getCommand(array $args)
    {
        throw new \RuntimeException("getCommand cannot be used in this command");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'writebasicauthusersfile';
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getRealPath($path)
    {
        if($path[0] == '~'){
            $path = $this->getHomeDirectory() . substr($path, 1);
        }
        return $path;
    }

    /**
     * @return string
     */
    protected function getHomeDirectory()
    {
        return trim(shell_exec('cd ~ && pwd'));
    }

    /**
     * @param string $plainpasswd
     * @return string
     * @see http://techtalk.virendrachandak.com/using-php-create-passwords-for-htpasswd-file/
     */
    protected function crypt_apr1_md5($plainpasswd)
    {
        $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $len = strlen($plainpasswd);
        $text = $plainpasswd.'$apr1$'.$salt;
        $bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
        $tmp = '';
        for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
        for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
        $bin = pack("H32", md5($text));
        for($i = 0; $i < 1000; $i++)
        {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3) $new .= $salt;
            if ($i % 7) $new .= $plainpasswd;
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }
        for ($i = 0; $i < 5; $i++)
        {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16) $j = 5;
            $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
        }
        $tmp = chr(0).chr(0).$bin[11].$tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
            "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");

        return "$"."apr1"."$".$salt."$".$tmp;
    }
}