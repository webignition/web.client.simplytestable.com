<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractEmailChangeControllerTest extends BaseSimplyTestableTestCase
{
    /**
     * @var EmailChangeController
     */
    protected $emailChangeController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->emailChangeController = new EmailChangeController();
        $this->emailChangeController->setContainer($this->container);
    }

    /**
     * @return array
     */
    abstract public function postRequestPublicUserDataProvider();

    /**
     * @dataProvider postRequestPublicUserDataProvider
     *
     * @param $routeName
     */
    public function testPostRequestPublicUser($routeName)
    {
        $router = $this->container->get('router');
        $requestUrl = $router->generate($routeName);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D',
            $response->getTargetUrl()
        );
    }
}
