<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    const TASK_ID = 2;

    public function setUp() {
        parent::setUp();
        $this->removeAllTests();

        $this->setHttpFixtures(array_merge(
            array(
                \Guzzle\Http\Message\Response::fromMessage('HTTP/1.0 200'),
                \Guzzle\Http\Message\Response::fromMessage($this->getRemoteTestSummaryHttpResponse())
            ),
            $this->getHttpFixtures($this->getFixturesDataPath()))
        );
        $this->setUser($this->makeUser());
    }

    protected function getRouteParameters() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => 1,
            'task_id' => $this->getTaskId()
        );
    }

    protected function getWebsite() {
        return self::WEBSITE;
    }

    protected function getTaskId() {
        return self::TASK_ID;
    }

    protected function getRemoteTestSummaryHttpResponse() {
        return <<<'EOD'
HTTP/1.1 200 OK
Content-Type: application/json

{
   "id":1,
   "user":"user@example.com",
   "website":"http:\/\/example.com\/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz",
   "state":"completed",
   "url_count":0,
   "task_count":1,
   "task_count_by_state":{
      "cancelled":0,
      "queued":0,
      "in-progress":0,
      "completed":1,
      "awaiting-cancellation":0,
      "queued-for-assignment":0,
      "failed-no-retry-available":0,
      "failed-retry-available":0,
      "failed-retry-limit-reached":0,
      "skipped":0
   },
   "task_types":[
      {
         "name":"HTML validation"
      },
      {
         "name":"CSS validation"
      },
      {
         "name":"JS static analysis"
      },
      {
         "name":"Link integrity"
      }
   ],
   "errored_task_count":0,
   "cancelled_task_count":0,
   "skipped_task_count":0,
   "warninged_task_count":0,
   "task_type_options":{
      "CSS validation":{
         "ignore-warnings":"1",
         "vendor-extensions":"warn",
         "ignore-common-cdns":"1"
      },
      "JS static analysis":{
         "ignore-common-cdns":"1",
         "jslint-option-unparam":"0",
         "jslint-option-continue":"0",
         "jslint-option-debug":"0",
         "jslint-option-evil":"0",
         "jslint-option-forin":"0",
         "jslint-option-sub":"0",
         "jslint-option-bitwise":"0",
         "jslint-option-browser":"1",
         "jslint-option-maxerr":"50",
         "jslint-option-indent":"4",
         "jslint-option-maxlen":"256",
         "jslint-option-eqeq":"1",
         "jslint-option-newcap":"1",
         "jslint-option-nomen":"1",
         "jslint-option-plusplus":"1",
         "jslint-option-regexp":"1",
         "jslint-option-sloppy":"1",
         "jslint-option-stupid":"1",
         "jslint-option-vars":"1",
         "jslint-option-white":"1",
         "jslint-option-anon":"1"
      },
      "Link integrity":{
         "excluded-domains":[
            "instagram.com"
         ]
      }
   },
   "type":"Full site",
   "is_public":false,
   "parameters":"",
   "error_count":0,
   "warning_count":0
}
EOD;

    }

}