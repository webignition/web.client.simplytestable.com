<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Functional\Controller\AbstractControllerTest;
use Tests\WebClientBundle\Services\HttpMockHandler;

abstract class AbstractUserAccountControllerTest extends AbstractControllerTest
{
    /**
     * @var HttpMockHandler
     */
    protected $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    /**
     * @return array
     */
    abstract public function postRequestPublicUserDataProvider();

    /**
     * @param string $routeName
     */
    public function assertPublicUserPostRequest($routeName)
    {
        $userManager = self::$container->get(UserManager::class);
        $router = self::$container->get('router');

        $userManager->setUser(SystemUserService::getPublicUser());
        $requestUrl = $router->generate($routeName);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect(
            '/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D'
        ));
    }
}
