<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends BaseViewController
{   
    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $viewData = array(            
            'public_site' => $this->container->getParameter('public_site'),              
            'user' => $this->getUser(),
            'is_logged_in' => true,
            'user_account_details_update_notice' => $this->getFlash('user_account_details_update_notice'),
            'user_account_details_update_email' => $this->getFlash('user_account_details_update_email')
        );
        
        if ($this->getUserEmailChangeRequestService()->hasEmailChangeRequest($this->getUser()->getUsername())) {
            $viewData['email_change_request'] = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());
            $viewData['token'] = $this->get('request')->query->get('token');
        }
        
        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:index.html.twig');       
        return $this->sendResponse($viewData);
    }
    
    protected function getNotLoggedInResponse() {
        if ($this->isLoggedIn()) {
            return null;
        }
        
        $redirectParameters = json_encode(array(
            'route' => 'user_account_index'
        ));

        $this->get('session')->setFlash('user_signin_error', 'account-not-logged-in');

        return $this->redirect($this->generateUrl('sign_in', array(
            'redirect' => base64_encode($redirectParameters)
        ), true));           
    } 
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService
     */
    protected function getUserEmailChangeRequestService() {
        return $this->get('simplytestable.services.useremailchangerequestservice');
    }
}