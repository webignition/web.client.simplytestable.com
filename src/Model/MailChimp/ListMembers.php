<?php

namespace App\Model\MailChimp;

class ListMembers
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->data['total_items'];
    }

    /**
     * @return array
     */
    public function getMembers()
    {
        return $this->data['members'];
    }

    /**
     * @return string[]
     */
    public function getMemberEmails()
    {
        $emails = [];
        $members = $this->getMembers();

        foreach ($members as $member) {
            $emails[] = $member['email_address'];
        }

        return $emails;
    }
}
