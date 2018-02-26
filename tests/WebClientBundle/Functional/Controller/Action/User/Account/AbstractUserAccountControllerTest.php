<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

abstract class AbstractUserAccountControllerTest extends AbstractBaseTestCase
{
    /**
     * @return array
     */
    abstract public function postRequestPublicUserDataProvider();

    /**
     * @param string $routeName
     */
    public function assertPublicUserPostRequest($routeName)
    {
        $userManager = $this->container->get(UserManager::class);
        $router = $this->container->get('router');

        $userManager->setUser(SystemUserService::getPublicUser());
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

        $this->assertTrue($response->isRedirect(
            'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D'
        ));
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return string
     */
    protected function createRequestUrl($routeName, array $routeParameters = [])
    {
        $router = $this->container->get('router');

        return $router->generate($routeName, $routeParameters);
    }
}