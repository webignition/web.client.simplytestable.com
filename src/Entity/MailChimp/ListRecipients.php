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
    private $recipients = [];

    public static function create(string $listId, array $recipients = []): ListRecipients
    {
        $listRecipients = new static();

        $listRecipients->listId = $listId;
        $listRecipients->addRecipients($recipients);

        return $listRecipients;
    }

    public function addRecipients(array $recipients)
    {
        foreach ($recipients as $recipient) {
            if (is_string($recipient)) {
                $this->addRecipient($recipient);
            }
        }
    }

    public function clearRecipients()
    {
        $this->recipients = [];
    }

    public function contains(string $recipient): bool
    {
        return in_array($recipient, $this->recipients);
    }

    public function addRecipient(string $recipient)
    {
        if (!$this->contains($recipient)) {
            $this->recipients[] = $recipient;
        }
    }

    public function removeRecipient(string $recipient)
    {
        if ($this->contains($recipient)) {
            unset($this->recipients[array_search($recipient, $this->recipients)]);
        }
    }
}
