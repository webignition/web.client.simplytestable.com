<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseController;

class TeamController extends BaseController {

    public function createAction() {
        $name = trim($this->getRequest()->request->get('name'));

        if ($name == '') {
            $this->get('session')->setFlash('team_create_error', 'blank-name');
            return $this->redirect($this->generateUrl('view_user_account_team_index_index', [], true));
        }

        $this->getTeamService()->setUser($this->getUser());
        $this->getTeamService()->create($name);

        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    public function inviteMemberAction() {
        $email = trim($this->getRequest()->request->get('email'));
        $invite = $this->getTeamService()->getInvite($email);

        try {
            $this->sendInviteEmail($invite['user'], $invite['team']);

            $flashData = [
                'status' => 'success',
                'invitee' => $invite['user'],
                'team' => $invite['team']
            ];

        } catch (\SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception $postmarkResponseException) {
            $flashData = [
                'status' => 'error',
                'error' => 'invalid-email'
            ];
        }

        $this->get('session')->getFlashBag()->set('team_invite_get', $flashData);
        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->get('simplytestable.services.teamservice');
    }


    /**
     *
     * @param string $invitee
     * @param  string $teamName
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendInviteEmail($invitee, $teamName) {
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_team_invite_invitation');

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($invitee);
        $message->setSubject(str_replace('{{team_name}}', $teamName, $messageProperties['subject']));
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:user-team-invite-invitation.txt.twig', array(
            'team_name' => $teamName,
            'account_team_page_url' => $this->generateUrl('view_user_account_team_index_index', [], true)
        )));

        $this->getMailService()->getSender()->send($message);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private function getMailService() {
        return $this->get('simplytestable.services.mail.service');
    }

}