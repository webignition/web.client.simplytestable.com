<?php

namespace SimplyTestable\WebClientBundle\Model\Team;

use SimplyTestable\WebClientBundle\Model\AbstractArrayBasedModel;

class Team extends AbstractArrayBasedModel
{
    /**
     * {@inheritdoc}
     */
    public function __construct($data)
    {
        parent::__construct($data);

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
