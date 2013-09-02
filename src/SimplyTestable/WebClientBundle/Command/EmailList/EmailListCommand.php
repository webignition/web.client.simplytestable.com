<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use SimplyTestable\WebClientBundle\Command\BaseCommand;

abstract class EmailListCommand extends BaseCommand
{
    
    protected function perform($methodName, $listId, $email)
    {             
        $this->getMailchimpService()->$methodName($listId, $email);
    }       
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailchimpService
     */
    private function getMailchimpService() {
        return $this->getContainer()->get('simplytestable.services.mailchimpservice');
    }
}