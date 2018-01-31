<?php

namespace SimplyTestable\WebClientBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;

/**
 * @ORM\Entity
 * @ORM\Table(name="TaskOutput",
 *     indexes={
 *         @ORM\Index(name="hash_idx", columns={"hash"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="SimplyTestable\WebClientBundle\Repository\TaskOutputRepository")
 */
class Output
{
    const TYPE_HTML_VALIDATION = 'HTML validation';
    const TYPE_CSS_VALIDATION = 'CSS validation';
    const TYPE_JS_STATIC_ANALYSIS = 'JS static analysis';
    const TYPE_LINK_INTEGRITY = 'Link integrity';

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $errorCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $warningCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=32)
     */
    protected $hash;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $content
     *
     * @return Output
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $type
     *
     * @return Output
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Result $result
     *
     * @return Output
     */
    public function setResult(Result $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    public function hasResult()
    {
        return !is_null($this->getResult());
    }

    /**
     * @param int $errorCount
     *
     * @return Output
     */
    public function setErrorCount($errorCount)
    {
        $this->errorCount = $errorCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->getErrorCount() > 0;
    }

    /**
     * @return bool
     */
    public function hasIssues()
    {
        return $this->hasErrors() || $this->hasWarnings();
    }

    /**
     * @param integer $warningCount
     *
     * @return Output
     */
    public function setWarningCount($warningCount)
    {
        $this->warningCount = $warningCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getWarningCount()
    {
        return $this->warningCount;
    }

    /**
     * @return bool
     */
    public function hasWarnings()
    {
        return $this->getWarningCount() > 0;
    }

    /**
     * @param string $hash
     *
     * @return Output
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return Output
     */
    public function generateHash()
    {
        return $this->setHash(md5('content:'.$this->getContent().'
        type:'.$this->getType().'
        error-count:'.$this->getErrorCount().'
        warning-count:'.$this->getWarningCount()));
    }

    /**
     * @return bool
     */
    public function hasId()
    {
        return !is_null($this->getId());
    }
}
