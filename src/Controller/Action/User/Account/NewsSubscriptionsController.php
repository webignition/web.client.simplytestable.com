<?php

namespace App\Controller\Action\User\Account;

use Doctrine\ORM\EntityManagerInterface;
use App\Exception\MailChimp\MemberExistsException;
use App\Exception\MailChimp\ResourceNotFoundException;
use App\Exception\MailChimp\UnknownException;
use App\Services\MailChimp\ListRecipientsService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Services\MailChimp\Service as MailChimpService;
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
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param ListRecipientsService $mailChimpListRecipientsService
     * @param MailChimpService $mailChimpService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        ListRecipientsService $mailChimpListRecipientsService,
        MailChimpService $mailChimpService,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($router, $userManager, $session);

        $this->mailChimpListRecipientsService = $mailChimpListRecipientsService;
        $this->mailChimpService = $mailChimpService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function updateAction(Request $request)
    {
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

        return $this->createUserAccountRedirectResponse('#news-subscriptions');
    }
}
