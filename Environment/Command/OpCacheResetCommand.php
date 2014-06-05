<?php

namespace Ibrows\DeployBundle\Environment\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class OpCacheResetCommand implements CommandInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port = 80;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var string
     */
    protected $routeName = 'ibrows_deploy_opcache_reset';

    /**
     * @var array
     */
    protected $routeParameters = array();

    /**
     * @var int
     */
    protected $timeout = 300;

    /**
     * @var string
     */
    const JSON_RESPONSE_KEY = 'opcacheresetsuccess';

    /**
     * @param RouterInterface $router
     * @param string $secret
     * @param string $host
     * @param int $port
     * @param string $baseUrl
     * @param string $method
     * @param string $routeName
     * @param array $routeParameters
     * @param int $timeout
     */
    public function __construct(RouterInterface $router, $secret, $host = null, $port = 80, $baseUrl = null, $method = 'POST', $routeName = 'ibrows_deploy_opcache_reset', array $routeParameters = array(), $timeout = 300)
    {
        $this->router = $router;
        $this->secret = $secret;
        $this->host = $host;
        $this->port = $port;
        $this->baseUrl = $baseUrl;
        $this->method = $method;
        $this->routeName = $routeName;
        $this->routeParameters = $routeParameters;
        $this->timeout = $timeout;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getArguments(array $args = array())
    {
        return array_merge(array(
            'host' => $this->host,
            'port' => $this->port,
            'baseUrl' => $this->baseUrl,
            'method' => $this->method,
            'routeName' => $this->routeName,
            'routeParameters' => $this->routeParameters,
            'timeout' => $this->timeout
        ), $args);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'opcachereset';
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

        $context = new RequestContext();

        if(!$args['host']){
            throw new \RuntimeException("Need host");
        }

        $context->setHost($args['host']);
        $context->setHttpPort($args['port']);
        $context->setBaseUrl($args['baseUrl']);

        $router = $this->router;
        $router->setContext($context);

        $url = $router->generate($this->routeName, $this->routeParameters, $router::ABSOLUTE_URL);

        $opts = array(
            'http' => array(
                'method' => $args['method'],
                'timeout' => $args['timeout'],
                'header' => "OpCacheSecret: ". $this->secret ."\r\n"
            )
        );

        $content = file_get_contents($url, false, stream_context_create($opts));

        if(
            !($result = @json_decode($content, true)) ||
            !isset($result['opcacheresetsuccess']) ||
            ! $result['opcacheresetsuccess']
        ){
            throw new \RuntimeException("OpCache-Reset WebServer Result not valid json - maybe route is protected over security.yml? -> Check ". $url);
        }
    }
}