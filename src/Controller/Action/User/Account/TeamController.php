<?php

namespace App\Controller\Action\User\Account;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Services\TeamService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param TeamService $teamService
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        TeamService $teamService
    ) {
        parent::__construct($router, $userManager, $session);

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

        $redirectResponse = $this->createRedirectResponse();
        $requestData = $request->request;
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

        return $this->createRedirectResponse();
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

        return $this->createRedirectResponse();
    }

    /**
     * @return RedirectResponse
     */
    private function createRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_user_account_team'));
    }
}
