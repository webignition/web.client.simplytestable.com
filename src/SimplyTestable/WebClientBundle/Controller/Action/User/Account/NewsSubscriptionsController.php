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
        $username = $user->getUsername();

        $flashData = [];

        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($this->get('request')->request->get($listName), FILTER_VALIDATE_BOOLEAN);

            $flashData[$listName] = [];

            $listRecipients = $mailChimpListRecipientsService->get($listName);
            $isSubscribed = $listRecipients->contains($username);

            if ($subscribeChoice === $isSubscribed) {
                $flashData[$listName] = $subscribeChoice ? 'already-subscribed' : 'already-unsubscribed';
                continue;
            }

            $listRecipients = $mailChimpListRecipientsService->get($listName);

            if ($subscribeChoice === true) {
                try {
                    $mailChimpService->subscribe($listName, $username);
                    $flashData[$listName] = 'subscribed';
                    $listRecipients->addRecipient($username);
                } catch (InvalidImportException $invalidImportException) {
                    if ($invalidImportException->getCode() == 220) {
                        $flashData[$listName] = 'subscribe-failed-banned';
                    } else {
                        $flashData[$listName] = 'subscribe-failed-unknown';
                    }
                }
            } else {
                $mailChimpService->unsubscribe($listName, $username);
                $flashData[$listName] = 'unsubscribed';
                $listRecipients->removeRecipient($username);
            }

            $entityManager->persist($listRecipients);
            $entityManager->flush();
        }

        $this->get('session')->getFlashBag()->set('user_account_newssubscriptions_update', $flashData);

        return $this->redirect($this->generateUrl('view_user_account_index_index', array(), true) . '#news-subscriptions');
    }
}
