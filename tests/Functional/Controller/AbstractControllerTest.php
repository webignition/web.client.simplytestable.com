<?php

namespace App\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use App\Tests\Functional\AbstractBaseTestCase;

abstract class AbstractControllerTest extends AbstractBaseTestCase
{
    const IE6_USER_AGENT = 'Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)';

    /**
     * @var RouterInterface
     */
    protected $router;

    protected function setUp()
    {
        parent::setUp();

        $this->router = self::$container->get(RouterInterface::class);
    }

    protected function issueIERequest(string $routeName, array $routeParameters = [])
    {
        $url = $this->router->generate($routeName, $routeParameters);

        $this->client->request('GET', $url, [], [], [
            'HTTP_USER_AGENT' => self::IE6_USER_AGENT,
        ]);
    }

    protected function assertIEFilteredRedirectResponse()
    {
        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(getenv('MARKETING_SITE'), $response->getTargetUrl());
    }
}
