<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
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

    public function updateAction()
    {
        $mailChimpListRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
        $mailChimpService = $this->container->get('simplytestable.services.mailchimpservice');

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $user = $this->getUser();
        $userName = $user->getUsername();

        $flashData = [];

        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($this->get('request')->request->get($listName), FILTER_VALIDATE_BOOLEAN);

            $flashData[$listName] = [];

            if ($this->subscribeChoiceMatchesCurrentSubscription($listName, $userName, $subscribeChoice)) {
                $flashData[$listName] = $subscribeChoice ? 'already-subscribed' : 'already-unsubscribed';
                continue;
            }

            $listRecipients = $mailChimpListRecipientsService->get($listName);

            if ($subscribeChoice === true) {
                try {
                    $mailChimpService->subscribe($listName, $userName);
                    $flashData[$listName] = 'subscribed';
                    $listRecipients->addRecipient($userName);
                } catch (InvalidImportException $invalidImportException) {
                    if ($invalidImportException->getCode() == 220) {
                        $flashData[$listName] = 'subscribe-failed-banned';
                    } else {
                        $flashData[$listName] = 'subscribe-failed-unknown';
                    }
                }
            } else {
                $mailChimpService->unsubscribe($listName, $userName);
                $flashData[$listName] = 'unsubscribed';
                $listRecipients->removeRecipient($userName);
            }

            $entityManager->persist($listRecipients);
            $entityManager->flush();
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