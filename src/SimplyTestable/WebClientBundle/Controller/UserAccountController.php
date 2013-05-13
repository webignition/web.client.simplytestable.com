<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountController extends BaseViewController
{   
    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:index.html.twig');       
        return $this->sendResponse(array(            
            'public_site' => $this->container->getParameter('public_site'),              
            'user' => $this->getUser(),
            'is_logged_in' => true,
        ));
    }
    
    private function getNotLoggedInResponse() {
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
}