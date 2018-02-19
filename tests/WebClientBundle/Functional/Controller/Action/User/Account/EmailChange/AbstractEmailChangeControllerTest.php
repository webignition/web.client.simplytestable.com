<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\EmailChange;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractEmailChangeControllerTest extends AbstractBaseTestCase
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

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
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
