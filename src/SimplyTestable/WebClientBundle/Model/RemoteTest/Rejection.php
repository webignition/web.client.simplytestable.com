<?php

namespace SimplyTestable\WebClientBundle\Model\RemoteTest;

class Rejection extends AbstractStandardObject
{
    /**
     * @return string
     */
    public function getReason()
    {
        return $this->getProperty('reason');
    }

    /**
     * @return array|null
     */
    public function getConstraint()
    {
        return $this->getProperty('constraint');
    }
}
