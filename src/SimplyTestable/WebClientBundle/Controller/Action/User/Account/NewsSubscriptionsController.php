<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Exception\MailChimp\MemberExistsException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\ResourceNotFoundException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\UnknownException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class NewsSubscriptionsController extends Controller implements RequiresPrivateUser
{
    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            [
                'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $mailChimpListRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
        $mailChimpService = $this->container->get('SimplyTestable\WebClientBundle\Services\MailChimp\Service');
        $userManager = $this->container->get(UserManager::class);
        $router = $this->container->get('router');

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $user = $userManager->getUser();
        $username = $user->getUsername();

        $flashData = [];

        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($request->request->get($listName), FILTER_VALIDATE_BOOLEAN);
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
                } catch (MemberExistsException $memberExistsException) {
                    $flashData[$listName] = 'subscribe-failed-unknown';
                } catch (UnknownException $unknownException) {
                    $flashData[$listName] = 'subscribe-failed-unknown';
                }
            } else {
                try {
                    $mailChimpService->unsubscribe($listName, $username);
                } catch (ResourceNotFoundException $resourceNotFoundException) {
                } catch (UnknownException $unknownException) {
                }

                $flashData[$listName] = 'unsubscribed';
                $listRecipients->removeRecipient($username);
            }

            $entityManager->persist($listRecipients);
            $entityManager->flush();
        }

        $this->get('session')->getFlashBag()->set('user_account_newssubscriptions_update', $flashData);

        return new RedirectResponse($router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ) . '#news-subscriptions');
    }
}
