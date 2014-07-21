<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\User\Account\Team\Index\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {
    
    protected function getHttpFixtureItems() {
        return array(
            $this->getUserSummaryHttpResponseFixture()
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
   },
   "team_summary":{
       "in":false,
       "has_invite":false
   }
}
EOD;
    }

}