<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamControllerCreateActionTest extends AbstractTeamControllerTest
{
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/team/';

    public function testCreateActionEmptyName()
    {
        $session = $this->container->get('session');

        $this->container->set('request', new Request());

        /* @var RedirectResponse $response */
        $response = $this->teamController->createAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(
            [
                TeamController::FLASH_BAG_CREATE_ERROR_KEY => [
                    TeamController::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK,
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    public function testCreateActionSuccess()
    {
        $session = $this->container->get('session');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->container->set('request', new Request([], [
            'name' => 'Team Name',
        ]));

        /* @var RedirectResponse $response */
        $response = $this->teamController->createAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals([], $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }
}
