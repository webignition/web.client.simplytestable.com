<?php
namespace AppBundle\Entity\MailChimp;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 */
class ListRecipients
{
    /**
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     *
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $listId;


    /**
     *
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $recipients;


    public function __construct() {
        $this->recipients = array();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
        $this->recipients = $recipients;

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
    public function contains($recipient) {
        return in_array($recipient, $this->getRecipients());
    }


    /**
     *
     * @param string $recipient
     * @return \AppBundle\Entity\MailChimp\ListRecipients
     */
    public function addRecipient($recipient) {
        if (!$this->contains($recipient)) {
            $this->recipients[] = $recipient;
        }

        return $this;
    }


    /**
     *
     * @param string $recipient
     * @return \AppBundle\Entity\MailChimp\ListRecipients
     */
    public function removeRecipient($recipient) {
        if ($this->contains($recipient)) {
            unset($this->recipients[array_search($recipient, $this->getRecipients())]);
        }

        return $this;
    }


    /**
     *
     * @return int
     */
    public function count() {
        return count($this->getRecipients());
    }

}