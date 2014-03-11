<?php
namespace SimplyTestable\WebClientBundle\Services;

use webignition\Model\Stripe\Event\Event as StripeEvent;
use SimplyTestable\WebClientBundle\Model\User;

class UserStripeEventService extends UserService {
    
    public function getList(User $user, $type) {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
                $this->getUrl('user_list_stripe_events', array(
                    'email' => $user->getUsername(),
                    'type' => $type
                ))
        );
        
        $this->addAuthorisationToRequest($request);
        
        $responseObject = $this->webResourceService->get($request)->getContentObject();
        
        $list = array();
        
        foreach ($responseObject as $eventData) {
            $list[] = new StripeEvent($eventData->stripe_event_data);
        };
        
        return $list;
    }
    
    public function getLatest(User $user, $type) {        
        $list = $this->getList($user, $type);
        if (count($list) === 0) {
            return null;
        }
        
        return $list[0];
    }
    
    
//    /**
//     * 
//     * @param \SimplyTestable\WebClientBundle\Model\User $user
//     * @param string $type
//     * @return array
//     */
//    public function getDataList(User $user, $type) {
//        $eventList = $this->getList($user, $type);
//        
//        var_dump($eventList);
//        exit();
//        
//        $dataList = array();
//        
//        ini_set('xdebug.var_display_max_data', 5000);
//        
//        foreach ($eventList as $event) {            
//            if (!isset($event->data)) {
//                var_dump($event);
//                exit();
//            }
//            
//            $dataList[] = json_decode($event->data);
//        }
//        
//        exit();
//       
//        return $dataList;
//    }
//    
//    
//    public function getLatestData(User $user, $type) {        
//        $list = $this->getDataList($user, $type);
//        if (count($list) === 0) {
//            return null;
//        }
//        
//        return $list[0];        
//    }
    
}