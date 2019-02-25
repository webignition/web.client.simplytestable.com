<?php
namespace App\Entity\MailChimp;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 */
class ListRecipients
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=16, unique=true)
     */
    private $listId = '';

    /**
     *
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $recipients;

    public function __construct()
    {
        $this->recipients = array();
    }

    /**
     *
     * @param string $listId
     * @return ListRecipients
     */
    public function setListId($listId)
    {
        $this->listId = $listId;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }


    /**
     * Set recipients
     *
     * @param array $recipients
     * @return ListRecipients
     */
    public function setRecipients($recipients)
    {
        foreach ($recipients as $recipient) {
            $this->addRecipient($recipient);
        }

        return $this;
    }

    /**
     * Get recipients
     *
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }


    /**
     *
     * @param string $recipient
     * @return boolean
     */
    public function contains($recipient)
    {
        return in_array($recipient, $this->getRecipients());
    }


    /**
     *
     * @param string $recipient
     * @return \App\Entity\MailChimp\ListRecipients
     */
    public function addRecipient($recipient)
    {
        if (!$this->contains($recipient)) {
            $this->recipients[] = $recipient;
        }

        return $this;
    }


    /**
     *
     * @param string $recipient
     * @return \App\Entity\MailChimp\ListRecipients
     */
    public function removeRecipient($recipient)
    {
        if ($this->contains($recipient)) {
            unset($this->recipients[array_search($recipient, $this->getRecipients())]);
        }

        return $this;
    }


    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->getRecipients());
    }
}
