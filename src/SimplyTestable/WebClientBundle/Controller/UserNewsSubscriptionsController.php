<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserNewsSubscriptionsController extends AbstractUserAccountController
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    public function updateAction()
    {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }

        $mailChimpListRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
        $mailChimpService = $this->container->get('simplytestable.services.mailchimpservice');

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $user = $this->getUser();
        $username = $user->getUsername();

        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($this->get('request')->request->get($listName), FILTER_VALIDATE_BOOLEAN);

            $listRecipients = $mailChimpListRecipientsService->get($listName);
            $isSubscribed = $listRecipients->contains($username);

            if ($subscribeChoice === $isSubscribed) {
                continue;
            }

            $listRecipients = $mailChimpListRecipientsService->get($listName);

            if ($subscribeChoice === true) {
                $mailChimpService->subscribe($listName, $username);
                $listRecipients->addRecipient($username);
            } else {
                $mailChimpService->unsubscribe($listName, $username);
                $listRecipients->removeRecipient($username);
            }

            $entityManager->persist($listRecipients);
            $entityManager->flush();
        }

        return $this->redirect($this->generateUrl('view_user_account_index_index', array(), true));
    }
}
