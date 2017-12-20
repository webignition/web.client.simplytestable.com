<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

class ListRecipientsService
{
    const EXCEPTION_LIST_NOT_KNOWN_MESSAGE = 'List "%s" is not known';
    const EXCEPTION_LIST_NOT_KNOWN_CODE = 1;
    const EXCEPTION_LIST_ID_NOT_KNOWN_MESSAGE = 'List id "%s" is not known';
    const EXCEPTION_LIST_ID_NOT_KNOWN_CODE = 2;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $listNameToListIdMap = [];

    /**
     * @param EntityManager $entityManager
     * @param LoggerInterface $logger
     * @param array $listIdentifiers
     */
    public function __construct(
        EntityManager $entityManager,
        LoggerInterface $logger,
        array $listIdentifiers
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->listNameToListIdMap = $listIdentifiers;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasListIdentifier($name)
    {
        return array_key_exists($name, $this->listNameToListIdMap);
    }

    /**
     * @param string $name
     *
     * @return string
     *
     * @throws \DomainException
     */
    public function getListId($name)
    {
        if (!$this->hasListIdentifier($name)) {
            throw new \DomainException(
                sprintf(self::EXCEPTION_LIST_NOT_KNOWN_MESSAGE, $name),
                self::EXCEPTION_LIST_NOT_KNOWN_CODE
            );
        }

        return $this->listNameToListIdMap[$name];
    }

    /**
     * @param string $listId
     *
     * @return string
     *
     * @throws \DomainException
     */
    public function getListName($listId)
    {
        if (!in_array($listId, $this->listNameToListIdMap)) {
            throw new \DomainException(
                sprintf(self::EXCEPTION_LIST_ID_NOT_KNOWN_MESSAGE, $listId),
                self::EXCEPTION_LIST_ID_NOT_KNOWN_CODE
            );
        }

        return array_search($listId, $this->listNameToListIdMap);
    }

    /**
     * @param string $name
     *
     * @return null|ListRecipients
     */
    public function get($name)
    {
        if (!$this->hasListIdentifier($name)) {
            throw new \DomainException(
                sprintf(self::EXCEPTION_LIST_NOT_KNOWN_MESSAGE, $name),
                self::EXCEPTION_LIST_NOT_KNOWN_CODE
            );
        }

        $listId = $this->listNameToListIdMap[$name];

        $entityRepository = $this->entityManager->getRepository(ListRecipients::class);
        $listRecipients = $entityRepository->findOneBy(array(
            'listId' => $listId
        ));

        if (empty($listRecipients)) {
            $listRecipients = new ListRecipients();
            $listRecipients->setListId($listId);
        }

        return $listRecipients;
    }
}
