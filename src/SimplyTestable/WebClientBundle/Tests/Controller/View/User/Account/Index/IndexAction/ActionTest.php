<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\User\Account\Index\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {
    
    protected function getHttpFixtureItems() {
        return array(
            $this->getUserSummaryHttpResponseFixture(),
            "HTTP/1.1 200 OK\nContent-Type: application/json\n\n[]",
            $this->getInvoiceCreatedHttpResponseFixture(),
            "HTTP/1.1 404 Not Found\nContent-Type: application/json\n\n{\"error\":{\"code\":404,\"message\":\"Not Found\"}}",
        );
    }
    
  
    protected function getExpectedResponseStatusCode() {
        return 200;
    }
    
    private function getUserSummaryHttpResponseFixture() {
return <<<'EOD'
HTTP/1.1 200 OK
Content-Type: application/json

{
   "email":"user@example.com",
   "user_plan":{
      "plan":{
         "name":"personal",
         "is_premium":true
      },
      "stripe_customer":"cus_3xZswUT4AVofI2",
      "start_trial_period":30
   },
   "stripe_customer":{
      "object":"customer",
      "created":1399029659,
      "livemode":false,
      "email":"user@example.com",
      "delinquent":false,
      "metadata":[

      ],
      "subscriptions":{
         "object":"list",
         "total_count":1,
         "has_more":false,
         "url":"\/v1\/customers\/cus_3xZswUT4AVofI2\/subscriptions",
         "data":[
            {
               "plan":{
                  "interval":"month",
                  "name":"Personal",
                  "created":1370600314,
                  "amount":900,
                  "currency":"gbp",
                  "object":"plan",
                  "livemode":false,
                  "interval_count":1,
                  "trial_period_days":30,
                  "metadata":[

                  ]
               },
               "object":"subscription",
               "start":1399029663,
               "status":"trialing",
               "customer":"cus_3xZswUT4AVofI2",
               "cancel_at_period_end":false,
               "current_period_start":1399029663,
               "current_period_end":1401621655,
               "trial_start":1399029663,
               "trial_end":1401621655,
               "quantity":1
            }
         ],
         "count":1
      },
      "account_balance":0,
      "currency":"gbp",
      "cards":{
         "object":"list",
         "total_count":1,
         "has_more":false,
         "url":"\/v1\/customers\/cus_3xZswUT4AVofI2\/cards",
         "data":[
            {
               "object":"card",
               "last4":"4242",
               "type":"Visa",
               "exp_month":1,
               "exp_year":2024,
               "fingerprint":"7y5WDQllCuyzj32D",
               "customer":"cus_3xZswUT4AVofI2",
               "country":"US",
               "name":"Foo Bar",
               "address_line1":"Address Line 1",
               "address_city":"Address City",
               "address_state":"Address State",
               "address_zip":"Address ZIP",
               "address_country":"GB",
               "cvc_check":"pass",
               "address_line1_check":"pass",
               "address_zip_check":"pass"
            }
         ],
         "count":1
      },
      "default_card":"card_3xa3R0XJwF4Owm",
      "active_card":{
         "object":"card",
         "last4":"4242",
         "type":"Visa",
         "exp_month":1,
         "exp_year":2024,
         "fingerprint":"7y5WDQllCuyzj32D",
         "customer":"cus_3xZswUT4AVofI2",
         "country":"US",
         "name":"Foo Bar",
         "address_line1":"Address Line 1",
         "address_city":"Address City",
         "address_state":"Address State",
         "address_zip":"Address ZIP",
         "address_country":"GB",
         "cvc_check":"pass",
         "address_line1_check":"pass",
         "address_zip_check":"pass"
      },
      "subscription":{
         "plan":{
            "interval":"month",
            "name":"Personal",
            "created":1370600314,
            "amount":900,
            "currency":"gbp",
            "object":"plan",
            "livemode":false,
            "interval_count":1,
            "trial_period_days":30,
            "metadata":[

            ]
         },
         "object":"subscription",
         "start":1399029663,
         "status":"trialing",
         "customer":"cus_3xZswUT4AVofI2",
         "cancel_at_period_end":false,
         "current_period_start":1399029663,
         "current_period_end":1401621655,
         "trial_start":1399029663,
         "trial_end":1401621655,
         "quantity":1
      },
      "id":"cus_3xZswUT4AVofI2"
   },
   "plan_constraints":{
      "credits":{
         "limit":5000,
         "used":0
      },
      "urls_per_job":50
   }
}
EOD;
    }
    
    
    private function getInvoiceCreatedHttpResponseFixture() {
return <<<'EOD'
HTTP/1.1 200 OK
Server: nginx/1.2.1
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/5.4.6-1ubuntu1.8
Set-Cookie: PHPSESSID=9i87n7selsph8am6u12ejkvrh2; path=/
Cache-Control: no-cache
Date: Mon, 05 May 2014 07:13:41 GMT

[{"stripe_id":"evt_3xZsuWyU75SedT","type":"invoice.created","is_live":false,"stripe_event_data":"{\n  \"id\": \"evt_3xZsuWyU75SedT\",\n  \"created\": 1399029663,\n  \"livemode\": false,\n  \"type\": \"invoice.created\",\n  \"data\": {\n    \"object\": {\n      \"date\": 1399029663,\n      \"id\": \"in_3xZsZBZaCeakYd\",\n      \"period_start\": 1399029663,\n      \"period_end\": 1399029663,\n      \"lines\": {\n        \"object\": \"list\",\n        \"total_count\": 1,\n        \"has_more\": false,\n        \"url\": \"\/v1\/invoices\/in_3xZsZBZaCeakYd\/lines\",\n        \"data\": [\n          {\n            \"id\": \"sub_3xZsTjObdGKedn\",\n            \"object\": \"line_item\",\n            \"type\": \"subscription\",\n            \"livemode\": false,\n            \"amount\": 0,\n            \"currency\": \"gbp\",\n            \"proration\": false,\n            \"period\": {\n              \"start\": 1399029663,\n              \"end\": 1401621655\n            },\n            \"quantity\": 1,\n            \"plan\": {\n              \"interval\": \"month\",\n              \"name\": \"Personal\",\n              \"created\": 1370600314,\n              \"amount\": 900,\n              \"currency\": \"gbp\",\n              \"id\": \"personal-9\",\n              \"object\": \"plan\",\n              \"livemode\": false,\n              \"interval_count\": 1,\n              \"trial_period_days\": 30,\n              \"metadata\": {\n              },\n              \"statement_description\": null\n            },\n            \"description\": null,\n            \"metadata\": null\n          }\n        ],\n        \"count\": 1\n      },\n      \"subtotal\": 0,\n      \"total\": 0,\n      \"customer\": \"cus_3xZswUT4AVofI2\",\n      \"object\": \"invoice\",\n      \"attempted\": true,\n      \"closed\": true,\n      \"paid\": true,\n      \"livemode\": false,\n      \"attempt_count\": 0,\n      \"amount_due\": 0,\n      \"currency\": \"gbp\",\n      \"starting_balance\": 0,\n      \"ending_balance\": null,\n      \"next_payment_attempt\": null,\n      \"charge\": null,\n      \"discount\": null,\n      \"application_fee\": null,\n      \"subscription\": \"sub_3xZsTjObdGKedn\",\n      \"metadata\": {\n      },\n      \"description\": null\n    }\n  },\n  \"object\": \"event\",\n  \"pending_webhooks\": 1,\n  \"request\": \"iar_3xZsOj5Hd7QTzF\"\n}","user":"user@example.com","is_processed":false}]   
EOD;
    }

}