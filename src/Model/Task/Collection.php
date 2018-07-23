<?php
namespace App\Model\Task;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Task\Task;

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
