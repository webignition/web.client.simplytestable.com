<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\TeamService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TeamController extends AbstractUserAccountController
{
    const FLASH_BAG_CREATE_ERROR_KEY = 'team_create_error';
    const FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK = 'blank-name';

    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * @param TeamService $teamService
     * @param UserManager $userManager
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        TeamService $teamService,
        UserManager $userManager,
        RouterInterface $router,
        SessionInterface $session
    ) {
        parent::__construct($userManager, $router, $session);

        $this->teamService = $teamService;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function createAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $redirectResponse = new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $name = trim($requestData->get('name'));

        if (empty($name)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_CREATE_ERROR_KEY,
                self::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK
            );

            return $redirectResponse;
        }

        $this->teamService->create($name);

        return $redirectResponse;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function removeMemberAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;
        $member = trim($requestData->get('user'));

        $this->teamService->removeFromTeam($member);

        return new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function leaveAction()
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $this->teamService->leave();

        return new RedirectResponse($this->router->generate(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
