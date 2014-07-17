<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements RequiresPrivateUser, IEFiltered {
    
    const STRIPE_CARD_CHECK_KEY_POSTFIX = '_check';

    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }

    public function indexAction() {
        $userSummary = $this->getUserService()->getSummary($this->getUser());

        return $this->renderResponse($this->getRequest(), [
            'plan_presentation_name' => $this->getPlanPresentationName($userSummary->getPlan()->getAccountPlan()->getName()),
            'user_summary' => $userSummary,
        ]);
    }


    /**
     *
     * @param string $plan
     * @return string
     */
    private function getPlanPresentationName($plan) {
        return ucwords($plan);
    }

}