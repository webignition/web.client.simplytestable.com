<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class InviteController extends BaseController {

    public function acceptAction($token) {
        if (!$this->getTeamInviteService()->hasForToken($token)) {
            return $this->redirect($this->generateUrl('view_user_signup_index_index', [], true));
        }

        $password = trim($this->getRequest()->request->get('password'));
        if ($password == '') {
            $this->get('session')->setFlash('invite_accept_error', 'blank-password');
            return $this->redirect($this->generateUrl('view_user_signup_invite_index', [
                'token' => $token
            ], true));
        }

        $invite = $this->getTeamInviteService()->getForToken($token);

        $response = $this->getUserService()->activateAndAccept($invite, $password);

        if ($response !== true) {
            $this->get('session')->setFlash('invite_accept_error', 'failure');
            $this->get('session')->setFlash('invite_accept_failure', $response);
            return $this->redirect($this->generateUrl('view_user_signup_invite_index', [
                'token' => $token
            ], true));
        }

        $this->getResqueQueueService()->add(
            'SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob',
            'email-list-subscribe',
            array(
                'listId' => 'announcements',
                'email' => $invite->getUser(),
            )
        );

        $this->getResqueQueueService()->add(
            'SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob',
            'email-list-subscribe',
            array(
                'listId' => 'introduction',
                'email' => $invite->getUser(),
            )
        );

        $this->get('session')->setFlash('user_signin_confirmation', 'user-activated');

        $redirectParameters = array(
            'email' => $invite->getUser()
        );

        if (!is_null($this->get('request')->cookies->get('simplytestable-redirect'))) {
            $redirectParameters['redirect'] = $this->get('request')->cookies->get('simplytestable-redirect');
        }

        return $this->redirect($this->generateUrl('view_user_signin_index', $redirectParameters, true));
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TeamInviteService
     */
    private function getTeamInviteService() {
        return $this->get('simplytestable.services.teaminviteservice');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\ResqueQueueService
     */
    private function getResqueQueueService() {
        return $this->container->get('simplytestable.services.resqueQueueService');
    }

}