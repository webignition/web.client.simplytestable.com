<?php

namespace SimplyTestable\WebClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="SimplyTestable\WebClientBundle\Repository\CacheValidatorHeadersRepository")
 * @ORM\Table(
 *     name="CacheValidatorHeaders",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="identifier_idx", columns={"identifier"})
 *     }
 * )
 */
class CacheValidatorHeaders
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $identifier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $lastModifiedDate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param \DateTime $lastModifiedDate
     */
    public function setLastModifiedDate(\DateTime $lastModifiedDate)
    {
        $this->lastModifiedDate = $lastModifiedDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastModifiedDate()
    {
        return $this->lastModifiedDate;
    }

    /**
     * @return string
     */
    public function getETag()
    {
        return md5($this->getIdentifier());
    }
}
