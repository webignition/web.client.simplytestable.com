<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnsubscribeCommand extends AbstractSubscriptionCommand
{
    const NAME = 'simplytestable:emaillist:unsubscribe';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::NAME)
            ->setDescription('Unsubscribe a user from a email list')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mailChimpService->unsubscribe(
            $input->getArgument(self::ARG_LIST_ID),
            $input->getArgument(self::ARG_EMAIL)
        );
    }
}
