<?php

namespace App\Controller\Action\User\Account;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Services\TeamService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class TeamController extends AbstractUserAccountTeamController
{
    const FLASH_BAG_CREATE_ERROR_KEY = 'team_create_error';
    const FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK = 'blank-name';

    /**
     * @var TeamService
     */
    private $teamService;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        TeamService $teamService
    ) {
        parent::__construct($router, $userManager, $flashBag);

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
        $redirectResponse = $this->createUserAccountTeamRedirectResponse();
        $requestData = $request->request;
        $name = trim($requestData->get('name'));

        if (empty($name)) {
            $this->flashBag->set(
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
        $requestData = $request->request;
        $member = trim($requestData->get('user'));

        $this->teamService->removeFromTeam($member);

        return $this->createUserAccountTeamRedirectResponse();
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
        $this->teamService->leave();

        return $this->createUserAccountTeamRedirectResponse();
    }
}
