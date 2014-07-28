<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Component\HttpFoundation\Cookie;

class InviteController extends BaseController {

    const ONE_YEAR_IN_SECONDS = 31536000;

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

        $user = new User();
        $user->setUsername($invite->getUser());
        $user->setPassword($password);

        $this->getUserService()->setUser($user);

        $staySignedIn = trim($this->get('request')->request->get('stay-signed-in')) == '' ? 0 : 1;

        $response = $this->redirect($this->generateUrl('view_dashboard_index_index', [], true));

        if ($staySignedIn == "1") {
            $stringifiedUser = $this->getUserSerializerService()->serializeToString($user);

            $cookie = new Cookie(
                'simplytestable-user',
                $stringifiedUser,
                time() + self::ONE_YEAR_IN_SECONDS,
                '/',
                '.simplytestable.com',
                false,
                true
            );

            $response->headers->setCookie($cookie);
        }

        return $response;
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