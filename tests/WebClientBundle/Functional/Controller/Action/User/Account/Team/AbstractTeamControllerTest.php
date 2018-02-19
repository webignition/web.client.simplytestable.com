<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractTeamControllerTest extends AbstractBaseTestCase
{
    /**
     * @var TeamController
     */
    protected $teamController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamController = new TeamController();
        $this->teamController->setContainer($this->container);
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
