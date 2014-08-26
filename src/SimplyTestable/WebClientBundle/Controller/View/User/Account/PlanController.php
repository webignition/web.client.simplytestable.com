<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PlanController extends BaseViewController implements RequiresPrivateUser, IEFiltered {

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getUserSignInRedirectResponse() {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_card_index']))
        ], true));
    }
    
    public function indexAction() {
        $userSummary = $this->getUserService()->getSummary($this->getUser());

        if ($userSummary->hasStripeCustomer() && $userSummary->getStripeCustomer()->hasDiscount()) {
            $priceModifier = (100 - $userSummary->getStripeCustomer()->getDiscount()->getCoupon()->getPercentOff()) / 100;
            $this->getPlansService()->setPriceModifier($priceModifier);
        }

        $viewData = array_merge([
            'user_summary' => $userSummary,
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName()),
            'plans' => $this->getPlansService()->listPremiumOnly()->getList(),
        ], $this->getViewFlashValues(array(
            'plan_subscribe_error',
            'plan_subscribe_success'
        )));

        if ($userSummary->getTeamSummary()->isInTeam()) {
            $this->getTeamService()->setUser($this->getUser());
            $viewData['team'] = $this->getTeamService()->getTeam();
        }

        return $this->renderResponse($this->getRequest(), $viewData);
    }


    /**
     *
     * @param string $plan
     * @return string
     */
    private function getPlanPresentationName($plan) {
        return ucwords($plan);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\PlansService
     */
    private function getPlansService() {
        return $this->container->get('simplytestable.services.plansService');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->container->get('simplytestable.services.teamservice');
    }

}