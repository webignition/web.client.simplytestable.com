<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use Symfony\Component\HttpFoundation\Response;

class UserAccountCardController extends AbstractUserAccountController {

    public function associateAction($stripe_card_token) {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        try {
            $this->getUserAccountCardService()->associate($this->getUser(), $stripe_card_token);
            return $this->redirect($this->generateUrl('view_user_account_index_index', array(), true));
        } catch (UserAccountCardException $userAccountCardException) {
            if ($this->requestIsForApplicationJson($this->getRequest())) {

                $response = new Response($this->getSerializer()->serialize([
                    'user_account_card_exception_message' => $userAccountCardException->getMessage(),
                    'user_account_card_exception_param' => $userAccountCardException->getParam(),
                    'user_account_card_exception_code' => $userAccountCardException->getStripeCode()
                ], 'json'));

                $response->headers->set('Content-Type', 'application/json');
                return $response;

            } else {
                $this->get('session')->setFlash('user_account_card_exception_message', $userAccountCardException->getMessage());
                $this->get('session')->setFlash('user_account_card_exception_param', $userAccountCardException->getParam());
                $this->get('session')->setFlash('user_account_card_exception_code', $userAccountCardException->getCode());                
                return $this->redirect($this->generateUrl('view_user_account_card_index', array(), true));
            }
        }
    }

    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserAccountCardService
     */
    protected function getUserAccountCardService() {
        return $this->get('simplytestable.services.useraccountcardservice');
    }


    protected function getAllowedContentTypes() {
        return array_merge(['application/json'], parent::getAllowedContentTypes());
    }

}