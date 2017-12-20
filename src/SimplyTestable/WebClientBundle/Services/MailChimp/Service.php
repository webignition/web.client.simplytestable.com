<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use ZfrMailChimp\Client\MailChimpClient;

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
     * @return boolean
     */
    public function listContains($listName, $email) {
        return $this->listRecipientsService->get($listName)->contains($email);
    }


    /**
     *
     * @param string $listName
     * @param string $email
     * @return boolean
     */
    public function subscribe($listName, $email) {
        if ($this->listContains($listName, $email)) {
            return true;
        }

        try {
            $this->client->subscribe(array(
                'id' => $this->listRecipientsService->getListId($listName),
                'email' => array(
                    'email' => $email
                ),
                'double_optin' => false
            ));
        } catch (\ZfrMailChimp\Exception\Ls\AlreadySubscribedException $alreadySubscribedException) {
        }

        return true;
    }


    /**
     *
     * @param string $listName
     * @param string $email
     * @return boolean
     */
    public function unsubscribe($listName, $email) {
        if (!$this->listContains($listName, $email)) {
            return true;
        }

        $this->client->unsubscribe(array(
            'id' => $this->listRecipientsService->getListId($listName),
            'email' => array(
                'email' => $email
            ),
            'delete_member' => false
        ));

        return true;
    }


    /**
     *
     * @param string $listName
     * @return array
     */
    public function retrieveMembers($listName) {
        $listLength = null;
        $currentPage = 0;
        $members = array();

        while (is_null($listLength) || count($members) < $listLength) {
            $response = $this->client->getListMembers(array(
                'id' => $this->listRecipientsService->getListId($listName),
                'opts' => array(
                    'limit' => self::LIST_MEMBERS_MAX_LIMIT,
                    'start' => $currentPage
                )
            ));

            if (is_null($listLength)) {
                $listLength = $response['total'];
            }

            $currentPage++;

            $members = array_merge($members, $response['data']);
        }

        return $members;
    }

}