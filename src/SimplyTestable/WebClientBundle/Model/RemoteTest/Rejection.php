<?php

namespace SimplyTestable\WebClientBundle\Model\RemoteTest;

use SimplyTestable\WebClientBundle\Model\AbstractArrayBasedModel;

class Rejection extends AbstractArrayBasedModel
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
