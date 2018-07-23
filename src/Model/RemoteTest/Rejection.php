<?php

namespace App\Model\RemoteTest;

use App\Model\AbstractArrayBasedModel;

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
