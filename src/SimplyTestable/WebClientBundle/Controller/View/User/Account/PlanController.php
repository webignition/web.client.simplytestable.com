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

        // user_summary.stripecustomer.hasDiscount

        if ($userSummary->hasStripeCustomer() && $userSummary->getStripeCustomer()->hasDiscount()) {
            $priceModifier = (100 - $userSummary->getStripeCustomer()->getDiscount()->getCoupon()->getPercentOff()) / 100;

//            var_dump($priceModifier);
//            exit();


//            $viewData['coupon'] = $this->getCouponService()->get();
            $this->getPlansService()->setPriceModifier($priceModifier);
        }
//        if ($userSummary->getTeamSummary()->isInTeam()) {
//            $this->getTeamService()->setUser($this->getUser());
//            $team = $this->getTeamService()->getTeam();
//
//            if ($team->getLeader() != $this->getUser()->getUsername()) {
//                return $this->redirect($this->generateUrl('view_user_account_index_index'));
//            }
//        }
//
//        $currentYear = date('Y');
//
//        $viewData = array_merge(array(
//            'public_site' => $this->container->getParameter('public_site'),
//            'user' => $this->getUser(),
//            'user_summary' => $userSummary,
//            'stripe_publishable_key' => $this->container->getParameter('stripe_publishable_key'),
//            'countries' => $this->getCountries(),
//            'is_logged_in' => true,
//            'expiry_year_start' => $currentYear,
//            'expiry_year_end' => $currentYear + 10,
//            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName())
//        ), $this->getViewFlashValues(array(
//            'user_account_card_exception_message',
//            'user_account_card_exception_param',
//            'user_account_card_exception_code'
//        )));
//
//        if ($userSummary->getTeamSummary()->isInTeam()) {
//            $viewData['team'] = $team;
//        }

        $viewData = [
            'user_summary' => $userSummary,
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName()),
            'plans' => $this->getPlansService()->listPremiumOnly()->getList(),
        ];

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

}