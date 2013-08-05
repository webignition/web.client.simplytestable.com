<?php
namespace SimplyTestable\WebClientBundle\Services;

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
        
        return $this->webResourceService->get($request)->getContentObject();
    }
    
    public function getLatest(User $user, $type) {        
        $list = $this->getList($user, $type);
        if (count($list) === 0) {
            return null;
        }
        
        return $list[0];
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\User $user
     * @param string $type
     * @return array
     */
    public function getDataList(User $user, $type) {
        $eventList = $this->getList($user, $type);
        $dataList = array();
        
        foreach ($eventList as $event) {
            $dataList[] = json_decode($event->data);
        }
       
        return $dataList;
    }
    
    
    public function getLatestData(User $user, $type) {        
        $list = $this->getDataList($user, $type);
        if (count($list) === 0) {
            return null;
        }
        
        return $list[0];        
    }
    
}