<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SubscribeCommand extends EmailListCommand
{
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:emaillist:subscribe')
            ->setDescription('Subscribe a user to a email list')
            ->addArgument('listId', InputArgument::REQUIRED, 'id of list to subscribe to')                
            ->addArgument('email', InputArgument::REQUIRED, 'email of user to subscribe')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {     
        $this->perform('subscribe', $input->getArgument('listId'), $input->getArgument('email'));
    }
  
}