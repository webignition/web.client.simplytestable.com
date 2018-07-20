<?php
namespace AppBundle\Model\Task;

use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Task\Task;

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
