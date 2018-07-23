<?php

namespace App\Services;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class CoreApplicationRouter implements WarmableInterface
{
    const ROUTING_RESOURCE = 'coreapplicationrouting.yml';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $host;

    /**
     * @param string $baseUrl
     * @param string $kernelProjectDirectory
     * @param string $cacheDir
     */
    public function __construct(
        $baseUrl,
        $kernelProjectDirectory,
        $cacheDir
    ) {
        $locator = new FileLocator($kernelProjectDirectory . '/app/config/resources');
        $requestContext = new RequestContext();
        $requestContext->fromRequest(Request::createFromGlobals());

        $this->router = new Router(
            new YamlFileLoader($locator),
            self::ROUTING_RESOURCE,
            ['cache_dir' => $cacheDir],
            $requestContext
        );

        $this->router->getContext()->setBaseUrl($baseUrl);

        $baseUrlParts = parse_url($baseUrl);
        $this->host = $baseUrlParts['host'];
    }

    /**
     * @see UrlGeneratorInterface::generate()
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    public function generate($name, $parameters = array())
    {
        $urlPlaceholders = [];
        $urlValues = [];

        foreach ($parameters as $key => $value) {
            if ($this->isUrl($value)) {
                $placeholder = md5($value);
                $urlPlaceholders[] = $placeholder;
                $urlValues[] = rawurlencode($value);
                $parameters[$key] = $placeholder;
            }
        }

        $url = $this->router->generate($name, $parameters);

        if (!empty($urlPlaceholders)) {
            $url = str_replace($urlPlaceholders, $urlValues, $url);
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $currentDir = $this->router->getOption('cache_dir');

        $this->router->setOption('cache_dir', $cacheDir);
        $this->router->getMatcher();
        $this->router->getGenerator();

        $this->router->setOption('cache_dir', $currentDir);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function isUrl($value)
    {
        if (!is_string($value)) {
            return false;
        }

        if ('https://' === substr($value, 0, strlen('https://'))) {
            return true;
        }

        if ('http://' === substr($value, 0, strlen('http://'))) {
            return true;
        }

        return false;
    }
}
