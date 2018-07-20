<?php

namespace AppBundle\Controller\View\User\Account\Team;

use AppBundle\Controller\View\User\Account\AbstractUserAccountController;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\FlashBagValues;
use AppBundle\Services\TeamInviteService;
use AppBundle\Services\TeamService;
use AppBundle\Services\UserManager;
use AppBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends AbstractUserAccountController
{
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    /**
     * @var TeamInviteService
     */
    private $teamInviteService;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UserService $userService
     * @param UserManager $userManager
     * @param TeamService $teamService
     * @param TeamInviteService $teamInviteService
     * @param FlashBagValues $flashBagValues
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UserService $userService,
        UserManager $userManager,
        TeamService $teamService,
        FlashBagValues $flashBagValues,
        TeamInviteService $teamInviteService
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheValidator,
            $userService,
            $userManager,
            $teamService,
            $flashBagValues
        );

        $this->teamInviteService = $teamInviteService;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserSignInRedirectResponseRoute()
    {
        return 'view_user_account_team_index_index';
    }

    /**
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction()
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();
        $userSummary = $this->userService->getSummary();
        $teamSummary = $userSummary->getTeamSummary();

        $viewData = array_merge(
            [
                'plan_presentation_name' => ucwords(
                    $userSummary->getPlan()->getAccountPlan()->getName()
                ),
                'user_summary' => $userSummary,
            ],
            $this->flashBagValues->get([
                'team_create_error',
                'team_invite_get',
                'team_invite_resend',
            ])
        );

        if ($teamSummary->isInTeam()) {
            $team = $this->teamService->getTeam();
            $viewData['team'] = $team;

            if ($team->getLeader() === $user->getUsername()) {
                $viewData['invites'] = $this->teamInviteService->getForTeam();
            }
        }

        if ($teamSummary->hasInvite()) {
            $viewData['invites'] = $this->teamInviteService->getForUser();
        }

        return $this->renderWithDefaultViewParameters('user-account-team.html.twig', $viewData);
    }
}