<?php
namespace SimplyTestable\WebClientBundle\Services\MailChimp;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

class ListRecipientsService {

    const ENTITY_NAME = 'SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients';


    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var EntityManager
     */
    private $entityManager;


    /**
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $entityRepository;


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
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository() {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->entityManager->getRepository(self::ENTITY_NAME);
        }

        return $this->entityRepository;
    }

    /**
     *
     * @param \SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients $listRecipients
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    public function persistAndFlush(ListRecipients $listRecipients) {
        $this->entityManager->persist($listRecipients);
        $this->entityManager->flush($listRecipients);

        return $this;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasListIdentifier($name) {
        return array_key_exists($name, $this->listNameToListIdMap);
    }


    /**
     *
     * @return array
     */
    public function getListNames() {
        return array_keys($this->listNameToListIdMap);
    }


    /**
     *
     * @param string $name
     * @return string
     * @throws \DomainException
     */
    public function getListId($name) {
        if (!$this->hasListIdentifier($name)) {
            throw new \DomainException('List "' . $name . '" is not known', 1);
        }

        return $this->listNameToListIdMap[$name];
    }


    /**
     *
     * @param string $listId
     * @return string
     * @throws \DomainException
     */
    public function getListName($listId) {
        if (!in_array($listId, $this->listNameToListIdMap)) {
            throw new \DomainException('List id "' . $listId . '" is not known', 2);
        }

        return array_search($listId, $this->listNameToListIdMap);
    }


    public function get($name) {
        if (!$this->hasListIdentifier($name)) {
            throw new \DomainException('List "' . $name . '" is not known', 1);
        }

        if (!$this->has($this->listNameToListIdMap[$name])) {
            $listRecipients = new ListRecipients();
            $listRecipients->setListId($this->listNameToListIdMap[$name]);

            return $listRecipients;
        }

        return $this->getEntityByListId($this->listNameToListIdMap[$name]);
    }


    /**
     *
     * @param string $listId
     * @return \SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients|null
     */
    private function getEntityByListId($listId) {
        return $this->getEntityRepository()->findOneBy(array(
            'listId' => $listId
        ));
    }


    private function has($listId) {
        return !is_null($this->getEntityByListId($listId));
    }

}