<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\View\User\Account\AbstractUserAccountController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use SimplyTestable\WebClientBundle\Services\TeamService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
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
     * @var FlashBagValues
     */
    private $flashBagValues;

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
        TeamInviteService $teamInviteService,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheValidator,
            $userService,
            $userManager,
            $teamService
        );

        $this->teamInviteService = $teamInviteService;
        $this->flashBagValues = $flashBagValues;
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

        return $this->renderWithDefaultViewParameters(
            'SimplyTestableWebClientBundle:bs3/User/Account/Team/Index:index.html.twig',
            $viewData
        );
    }
}
