<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use SimplyTestable\WebClientBundle\Command\BaseCommand;

abstract class EmailListCommand extends BaseCommand {    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\Service
     */
    protected function getMailchimpService() {
        return $this->getContainer()->get('simplytestable.services.mailchimpservice');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    protected function getMailchimpListRecipientsService() {
        return $this->getContainer()->get('simplytestable.services.mailchimp.listRecipients');
    }    
}