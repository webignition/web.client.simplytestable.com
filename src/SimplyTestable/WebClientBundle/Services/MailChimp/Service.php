<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use ZfrMailChimp\Client\MailChimpClient;
use ZfrMailChimp\Exception\Ls\AlreadySubscribedException;

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
     * @param MailChimpClient $mailChimpClient
     * @param ListRecipientsService $listRecipientsService
     */
    public function __construct(MailChimpClient $mailChimpClient, ListRecipientsService $listRecipientsService)
    {
        $this->client = $mailChimpClient;
        $this->listRecipientsService = $listRecipientsService;
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
    public function retrieveMembers($listName)
    {
        $listLength = null;
        $currentPage = 0;
        $members = [];

        while (is_null($listLength) || count($members) < $listLength) {
            $response = $this->client->getListMembers([
                'id' => $this->listRecipientsService->getListId($listName),
                'opts' => [
                    'limit' => self::LIST_MEMBERS_MAX_LIMIT,
                    'start' => $currentPage
                ]
            ]);

            if (is_null($listLength)) {
                $listLength = $response['total'];
            }

            $currentPage++;

            $members = array_merge($members, $response['data']);
        }

        return $members;
    }
}
