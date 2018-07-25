<?php

namespace App\Tests\Functional\Controller\View\User\Account;

use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;

abstract class AbstractAccountControllerTest extends AbstractViewControllerTest
{
    /**
     * @return array
     */
    abstract protected function invalidUserGetRequestDataProvider();

    /**
     * @dataProvider invalidUserGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $routeName
     * @param string $expectedRedirectUrl
     */
    public function testInvalidUserGetRequest(array $httpFixtures, string $routeName, string $expectedRedirectUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->router->generate($routeName)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }
}
