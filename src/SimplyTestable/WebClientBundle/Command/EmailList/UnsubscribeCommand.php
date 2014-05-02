<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UnsubscribeCommand extends EmailListCommand
{
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:emaillist:unsubscribe')
            ->setDescription('Unsubscribe a user from a email list')
            ->addArgument('listId', InputArgument::REQUIRED, 'id of list to unsubscribe from')                
            ->addArgument('email', InputArgument::REQUIRED, 'email of user to unsubscribe')
        ;
    }
 

    protected function execute(InputInterface $input, OutputInterface $output) {     
        $this->getMailchimpService()->unsubscribe($input->getArgument('listId'), $input->getArgument('email'));
    }
 
}