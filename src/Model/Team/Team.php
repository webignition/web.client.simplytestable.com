<?php

namespace App\Model\Team;

use App\Model\AbstractArrayBasedModel;

class Team extends AbstractArrayBasedModel
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $source)
    {
        parent::__construct($source);

        if (is_null($this->getProperty('people'))) {
            $this->setProperty('people', [
                $this->getLeader()
            ]);
        }
    }

    /**
     * @return string
     */
    public function getLeader()
    {
        return $this->getProperty('team')['leader'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getProperty('team')['name'];
    }

    /**
     * @return string[]
     */
    public function getMembers()
    {
        return $this->getProperty('members');
    }

    /**
     * @return bool
     */
    public function hasMembers()
    {
        return !empty($this->getMembers());
    }
}
