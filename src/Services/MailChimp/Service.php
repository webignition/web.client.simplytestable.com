<?php

namespace App\Services\MailChimp;

use App\Exception\MailChimp\MemberExistsException;
use App\Exception\MailChimp\ResourceNotFoundException;
use App\Exception\MailChimp\UnknownException;
use App\Services\MailChimp\Client as MailChimpClient;

class Service
{
    const LIST_MEMBERS_MAX_LIMIT = 100;

    /**
     *
     * @var ListRecipientsService
     */
    private $listRecipientsService;

    /**
     * @var MailChimpClient
     */
    private $fooClient;

    /**
     * @param MailChimpClient $fooMailChimpClient
     * @param ListRecipientsService $listRecipientsService
     */
    public function __construct(MailChimpClient $fooMailChimpClient, ListRecipientsService $listRecipientsService)
    {
        $this->fooClient = $fooMailChimpClient;
        $this->listRecipientsService = $listRecipientsService;
    }

    /**
     * @param string $listName
     * @param string $email
     *
     * @return bool
     *
     * @throws MemberExistsException
     * @throws UnknownException
     */
    public function subscribe($listName, $email)
    {
        $listRecipients = $this->listRecipientsService->get($listName);
        if ($listRecipients->contains($email)) {
            return true;
        }

        $this->fooClient->addListMember(
            $this->listRecipientsService->getListId($listName),
            $email
        );

        return true;
    }

    /**
     * @param string $listName
     * @param string $email
     *
     * @return bool
     *
     * @throws UnknownException
     * @throws ResourceNotFoundException
     */
    public function unsubscribe($listName, $email)
    {
        $listRecipients = $this->listRecipientsService->get($listName);
        if (!$listRecipients->contains($email)) {
            return true;
        }

        $this->fooClient->removeListMember(
            $this->listRecipientsService->getListId($listName),
            $email
        );

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