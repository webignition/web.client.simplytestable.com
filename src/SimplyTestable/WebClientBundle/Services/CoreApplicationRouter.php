<?php
namespace SimplyTestable\WebClientBundle\Services;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class CoreApplicationRouter
{
    const BUNDLE_CONFIG_PATH = '@SimplyTestableWebClientBundle/Resources/config';
    const ROUTING_RESOURCE = 'coreapplicationrouting.yml';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param $baseUrl
     * @param ResourceLocator $resourceLocator
     * @param string $cacheDir
     */
    public function __construct(
        $baseUrl,
        ResourceLocator $resourceLocator,
        $cacheDir
    ) {
        $locator = new FileLocator($resourceLocator->locate(self::BUNDLE_CONFIG_PATH));
        $requestContext = new RequestContext();
        $requestContext->fromRequest(Request::createFromGlobals());

        $this->router = new Router(
            new YamlFileLoader($locator),
            self::ROUTING_RESOURCE,
            ['cache_dir' => $cacheDir],
            $requestContext
        );

        $this->router->getContext()->setBaseUrl($baseUrl);
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
