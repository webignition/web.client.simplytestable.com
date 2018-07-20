<?php

namespace AppBundle\Resque\Job;

abstract class AbstractEmailListJob extends CommandJob
{
    /**
     * {@inheritdoc}
     */
    protected function getCommandArgs()
    {
        return [
            'listId' => $this->args['listId'],
            'email' => $this->args['email']
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdentifier()
    {
        return sprintf(
            '%s:%s',
            $this->args['listId'],
            $this->args['email']
        );
    }
}
