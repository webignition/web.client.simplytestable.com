<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\Service;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class ServiceTest extends BaseSimplyTestableTestCase {

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\Service
     */
    protected function getMailchimpService() {
        return $this->container->get('simplytestable.services.mailchimpservice');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    protected function getMailChimpListRecipientsService() {
        return $this->container->get('simplytestable.services.mailchimp.listRecipients');
    }

}
