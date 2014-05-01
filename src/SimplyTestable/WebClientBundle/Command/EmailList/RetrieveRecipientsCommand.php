<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RetrieveRecipientsCommand extends EmailListCommand
{
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:emaillist:retrieve-recipients')
            ->setDescription('Retrieve recipients for a given list')
            ->addArgument('listName', InputArgument::REQUIRED, 'name of list to retrieve')                
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output) {   
        if (!($this->getMailchimpListRecipientsService()->hasListIdentifier($input->getArgument('listName')))) {
            return 0;
        }
        
        $listRecipients = $this->getMailchimpListRecipientsService()->get($input->getArgument('listName'));
        $listRecipients->setRecipients(array());
        
        $members = $this->getMailchimpService()->retrieveMembers($input->getArgument('listName'));
        
        foreach ($members as $member) {            
            $listRecipients->addRecipient($member['email']);
        }
        
        $this->getMailchimpListRecipientsService()->persistAndFlush($listRecipients);
    }
  
}