<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ZfrMailChimp\Exception\Ls\InvalidImportException;

class NewsSubscriptionsController extends BaseController implements RequiresPrivateUser {

    /**
     *
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse() {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], true));
    }

    public function updateAction() {
        $flashData = [];

        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($this->get('request')->request->get($listName), FILTER_VALIDATE_BOOLEAN);

            $flashData[$listName] = [];

            if ($this->subscribeChoiceMatchesCurrentSubscription($listName, $this->getUser()->getUsername(), $subscribeChoice)) {
                $flashData[$listName] = $subscribeChoice ? 'already-subscribed' : 'already-unsubscribed';
                continue;
            }

            $listRecipients = $this->getMailchimpListRecipientsService()->get($listName);

            if ($subscribeChoice === true) {
                try {
                    $this->getMailchimpService()->subscribe($listName, $this->getUser()->getUsername());
                    $flashData[$listName] = 'subscribed';
                    $listRecipients->addRecipient($this->getUser()->getUsername());
                } catch (InvalidImportException $invalidImportException) {
                    if ($invalidImportException->getCode() == 220) {
                        $flashData[$listName] = 'subscribe-failed-banned';
                    } else {
                        $flashData[$listName] = 'subscribe-failed-unknown';
                    }
                }
            } else {
                $this->getMailchimpService()->unsubscribe($listName, $this->getUser()->getUsername());
                $flashData[$listName] = 'unsubscribed';
                $listRecipients->removeRecipient($this->getUser()->getUsername());
            }

            $this->getMailchimpListRecipientsService()->persistAndFlush($listRecipients);
        }

        $this->get('session')->getFlashBag()->set('user_account_newssubscriptions_update', $flashData);

        return $this->redirect($this->generateUrl('view_user_account_index_index', array(), true) . '#news-subscriptions');
    }


    /**
     *
     * @param string $listName
     * @param string $email
     * @param boolean $subscribeChoice
     * @return boolean
     */
    private function subscribeChoiceMatchesCurrentSubscription($listName, $email, $subscribeChoice) {
        return $subscribeChoice === $this->getMailchimpService()->listContains($listName, $email);
    }




    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\Service
     */
    private function getMailchimpService() {
        return $this->container->get('simplytestable.services.mailchimpservice');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    private function getMailchimpListRecipientsService() {
        return $this->container->get('simplytestable.services.mailchimp.listRecipients');
    }

}