<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TeamController extends BaseController implements RequiresPrivateUser
{
    const FLASH_BAG_CREATE_ERROR_KEY = 'team_create_error';
    const FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK = 'blank-name';

    /**
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    /**
     * @return RedirectResponse
     */
    public function createAction()
    {
        $session = $this->container->get('session');
        $request = $this->container->get('request');
        $teamService = $this->container->get('simplytestable.services.teamservice');

        $requestData = $request->request;

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_account_team_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $name = trim($requestData->get('name'));

        if (empty($name)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_CREATE_ERROR_KEY,
                self::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK
            );

            return $redirectResponse;
        }

        $teamService->setUser($this->getUser());
        $teamService->create($name);

        return $redirectResponse;
    }


    public function inviteMemberAction() {
        $redirectResponse = $this->redirect($this->generateUrl('view_user_account_team_index_index'));

        $invitee = trim($this->getRequest()->request->get('email'));

        if (!$this->isEmailValid($invitee)) {
            $flashData = [
                'status' => 'error',
                'error' => 'invalid-invitee',
                'invitee' => $invitee,
            ];

            $this->get('session')->getFlashBag()->set('team_invite_get', $flashData);
            return $redirectResponse;
        }

        if ($invitee == $this->getUser()->getUsername()) {
            $flashData = [
                'status' => 'error',
                'error' => 'invite-self'
            ];

            $this->get('session')->getFlashBag()->set('team_invite_get', $flashData);
            return $redirectResponse;
        }

        try {
            $invite = $this->getTeamInviteService()->get($invitee);

            if ($this->getUserService()->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($invite);
            } else {
                $this->sendInviteActivationEmail($invite);
            }

            $flashData = [
                'status' => 'success',
                'invitee' => $invite->getUser(),
                'team' => $invite->getTeam()
            ];
        } catch (TeamServiceException $teamServiceException) {
            $flashData = [
                'status' => 'error',
                'error' => $this->getFlashErrorCodeFromTeamServiceException($teamServiceException),
                'invitee' => $invitee
            ];
        } catch (PostmarkResponseException $postmarkResponseException) {
            $flashData = [
                'status' => 'error',
                'error' => $this->getFlashErrorCodeFromPostmarkResponseException($postmarkResponseException),
                'invitee' => $invitee,
            ];

            $invite = new Invite([
                'user' => $invitee
            ]);

            $this->getTeamInviteService()->removeForUser($invite);
        }

        $this->get('session')->getFlashBag()->set('team_invite_get', $flashData);

        return $redirectResponse;
    }


    /**
     * @param PostmarkResponseException $postmarkResponseException
     * @return string
     */
    private function getFlashErrorCodeFromPostmarkResponseException(PostmarkResponseException $postmarkResponseException) {
        if ($postmarkResponseException->isNotAllowedToSendException()) {
            return 'postmark-not-allowed-to-send';
        } elseif ($postmarkResponseException->isInactiveRecipientException()) {
            return 'postmark-inactive-recipient';
        } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
            return 'invalid-email';
        }

        return 'postmark-failure';
    }


    /**
     * @param TeamServiceException $teamServiceException
     * @return string
     */
    private function getFlashErrorCodeFromTeamServiceException(TeamServiceException $teamServiceException) {
        if ($teamServiceException->isInviteeIsATeamLeaderException()) {
            return 'invitee-is-a-team-leader';
        }

        if ($teamServiceException->isUserIsAlreadyOnATeamException()) {
            return 'invitee-is-already-on-a-team';
        }

        if ($teamServiceException->isInviteeHasAPremiumPlanException()) {
            return 'invitee-has-a-premium-plan';
        }

        return 'invitee-unknown-error';
    }



    public function respondInviteAction() {
        $response = trim($this->getRequest()->request->get('response'));

        if (!in_array($response, ['accept', 'decline'])) {
            return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
        }

        $team = trim($this->getRequest()->request->get('team'));

        if ($response == 'decline') {
            $this->getTeamInviteService()->declineInvite(new Invite([
                'user' => $this->getUser()->getUsername(),
                'team' => $team
            ]));
        }

        if ($response == 'accept') {
            $this->getTeamInviteService()->acceptInvite(new Invite([
                'user' => $this->getUser()->getUsername(),
                'team' => $team
            ]));
        }

        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    public function removeInviteAction() {
        $invitee = trim($this->getRequest()->request->get('user'));

        $this->getTeamInviteService()->removeForUser(new Invite([
            'user' => $invitee
        ]));

        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    public function removeMemberAction() {
        $member = trim($this->getRequest()->request->get('user'));

        $this->getTeamService()->removeFromTeam($member);

        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    public function resendInviteAction() {
        $invitee = trim($this->getRequest()->request->get('user'));
        $flashData = [];

        try {
            $invite = $this->getTeamInviteService()->get($invitee);

            if ($this->getUserService()->isEnabled($invite->getUser())) {
                $this->sendInviteEmail($invite);
            } else {
                $this->sendInviteActivationEmail($invite);
            }

            $flashData = [
                'status' => 'success',
                'invitee' => $invite->getUser(),
                'team' => $invite->getTeam()
            ];
        } catch (TeamServiceException $teamServiceException) {
            if ($teamServiceException->isInviteeIsATeamLeaderException()) {
                $flashData = [
                    'status' => 'error',
                    'error' => 'invitee-is-a-team-leader',
                    'invitee' => $invitee
                ];
            }
        } catch (PostmarkResponseException $postmarkResponseException) {
            $flashData = [
                'status' => 'error',
                'error' => $this->getFlashErrorCodeFromPostmarkResponseException($postmarkResponseException),
                'invitee' => $invitee,
            ];
        }

        $this->get('session')->getFlashBag()->set('team_invite_resend', $flashData);
        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    public function leaveAction() {
        $this->getTeamService()->leave();
        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }

    /**
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->get('simplytestable.services.teamservice');
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TeamInviteService
     */
    private function getTeamInviteService() {
        return $this->get('simplytestable.services.teaminviteservice');
    }


    /**
     *
     * @param Invite $invite
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendInviteEmail(Invite $invite) {
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_team_invite_invitation');

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($invite->getUser());
        $message->setSubject(str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']));
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:user-team-invite-invitation.txt.twig', array(
            'team_name' => $invite->getTeam(),
            'account_team_page_url' => $this->generateUrl('view_user_account_team_index_index', [], true)
        )));

        $this->getMailService()->getSender()->send($message);
    }


    /**
     * @param Invite $invite
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendInviteActivationEmail(Invite $invite) {
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_team_invite_newuser_invitation');

        $confirmationUrl = $this->generateUrl('view_user_signup_invite_index', array(
                'token' => $invite->getToken()
            ), true);

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($invite->getUser());
        $message->setSubject(str_replace('{{team_name}}', $invite->getTeam(), $messageProperties['subject']));
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:user-team-invite-newuser-invitation.txt.twig', array(
            'team_name' => $invite->getTeam(),
            'confirmation_url' => $confirmationUrl
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


    /**
     *
     * @param string $email
     * @return boolean
     */
    private function isEmailValid($email) {
        $validator = new EmailValidator;
        return $validator->isValid($email);
    }

}