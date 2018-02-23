<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Exception\MailChimp\MemberExistsException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\ResourceNotFoundException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\UnknownException;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;
use Symfony\Component\Routing\RouterInterface;

class NewsSubscriptionsController extends AbstractUserAccountController
{
    /**
     * @var ListRecipientsService
     */
    private $mailChimpListRecipientsService;

    /**
     * @var MailChimpService
     */
    private $mailChimpService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param ListRecipientsService $mailChimpListRecipientsService
     * @param MailChimpService $mailChimpService
     * @param EntityManagerInterface $entityManager
     * @param UserManager $userManager
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        ListRecipientsService $mailChimpListRecipientsService,
        MailChimpService $mailChimpService,
        EntityManagerInterface $entityManager,
        UserManager $userManager,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->mailChimpListRecipientsService = $mailChimpListRecipientsService;
        $this->mailChimpService = $mailChimpService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function updateAction(Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $flashData = [];

        foreach (['announcements', 'updates'] as $listName) {
            $subscribeChoice = filter_var($request->request->get($listName), FILTER_VALIDATE_BOOLEAN);
            $flashData[$listName] = [];

            $listRecipients = $this->mailChimpListRecipientsService->get($listName);
            $isSubscribed = $listRecipients->contains($username);

            if ($subscribeChoice === $isSubscribed) {
                $flashData[$listName] = $subscribeChoice ? 'already-subscribed' : 'already-unsubscribed';
                continue;
            }

            $listRecipients = $this->mailChimpListRecipientsService->get($listName);

            if ($subscribeChoice === true) {
                try {
                    $this->mailChimpService->subscribe($listName, $username);
                    $flashData[$listName] = 'subscribed';
                    $listRecipients->addRecipient($username);
                } catch (MemberExistsException $memberExistsException) {
                    $flashData[$listName] = 'subscribe-failed-unknown';
                } catch (UnknownException $unknownException) {
                    $flashData[$listName] = 'subscribe-failed-unknown';
                }
            } else {
                try {
                    $this->mailChimpService->unsubscribe($listName, $username);
                } catch (ResourceNotFoundException $resourceNotFoundException) {
                } catch (UnknownException $unknownException) {
                }

                $flashData[$listName] = 'unsubscribed';
                $listRecipients->removeRecipient($username);
            }

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }

        $this->session->getFlashBag()->set('user_account_newssubscriptions_update', $flashData);

        return new RedirectResponse($this->router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ) . '#news-subscriptions');
    }
}
