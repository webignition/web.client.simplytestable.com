<?php
namespace SimplyTestable\WebClientBundle\Model\Task;

use Doctrine\Common\Collections\ArrayCollection;
use SimplyTestable\WebClientBundle\Entity\Task\Task;

class Collection extends ArrayCollection
{
    /**
     * @return string
     */
    public function getHash()
    {
        $content = '';

        foreach ($this as $task) {
            /* @var Task $task */
            $content .= $task->getTaskId() . ':' . $task->getState() . '::';
        }

        return md5($content);
    }
}
