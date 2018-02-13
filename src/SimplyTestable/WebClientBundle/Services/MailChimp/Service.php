<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use ZfrMailChimp\Client\MailChimpClient;
use ZfrMailChimp\Exception\Ls\AlreadySubscribedException;
use SimplyTestable\WebClientBundle\Services\MailChimp\Client as FooMailChimpClient;

class Service
{
    const LIST_MEMBERS_MAX_LIMIT = 100;

    /**
     * @var MailChimpClient
     */
    private $client;

    /**
     *
     * @var ListRecipientsService
     */
    private $listRecipientsService;

    /**
     * @var FooMailChimpClient
     */
    private $fooClient;

    /**
     * @param MailChimpClient $mailChimpClient
     * @param ListRecipientsService $listRecipientsService
     * @param Client $fooMailChimpClient
     */
    public function __construct(
        MailChimpClient $mailChimpClient,
        ListRecipientsService $listRecipientsService,
        FooMailChimpClient $fooMailChimpClient
    ) {
        $this->client = $mailChimpClient;
        $this->listRecipientsService = $listRecipientsService;
        $this->fooClient = $fooMailChimpClient;
    }

    /**
     *
     * @param string $listName
     * @param string $email
     *
     * @return bool
     */
    public function subscribe($listName, $email)
    {
        $listRecipients = $this->listRecipientsService->get($listName);
        if ($listRecipients->contains($email)) {
            return true;
        }

        try {
            $this->client->subscribe([
                'id' => $this->listRecipientsService->getListId($listName),
                'email' => [
                    'email' => $email
                ],
                'double_optin' => false
            ]);
        } catch (AlreadySubscribedException $alreadySubscribedException) {
        }

        return true;
    }

    /**
     * @param string $listName
     * @param string $email
     *
     * @return bool
     */
    public function unsubscribe($listName, $email)
    {
        $listRecipients = $this->listRecipientsService->get($listName);
        if (!$listRecipients->contains($email)) {
            return true;
        }

        $this->client->unsubscribe([
            'id' => $this->listRecipientsService->getListId($listName),
            'email' => [
                'email' => $email
            ],
            'delete_member' => false
        ]);

        return true;
    }

    /**
     * @param string $listName
     *
     * @return string[]
     */
    public function retrieveMemberEmails($listName)
    {
        $listLength = null;
        $memberEmails = [];

        while (is_null($listLength) || count($memberEmails) < $listLength) {
            $listMembers = $this->fooClient->getListMembers(
                $this->listRecipientsService->getListId($listName),
                self::LIST_MEMBERS_MAX_LIMIT,
                count($memberEmails)
            );

            $memberEmails = array_merge($memberEmails, $listMembers->getMemberEmails());

            if (is_null($listLength)) {
                $listLength = $listMembers->getTotalItems();
            }
        }

        return $memberEmails;
    }
}
