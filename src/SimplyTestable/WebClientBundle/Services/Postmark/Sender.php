<?php
namespace SimplyTestable\WebClientBundle\Services\Postmark;

use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class Sender {   
    
    
    /**
     * 
     * @param \MZ\PostmarkBundle\Postmark\Message $message
     * @return \SimplyTestable\WebClientBundle\Model\Postmark\Response
     */
    public function send(\MZ\PostmarkBundle\Postmark\Message $message) {
        $response = new PostmarkResponse($this->getJsonRespnse($message));
        
        if ($response->isError()) {
            throw new PostmarkResponseException($response->getMessage(), $response->getErrorCode());
        }
        
        return $response;
    }
    
    
    /**
     * 
     * @param \MZ\PostmarkBundle\Postmark\Message $message
     * @return string
     */
    protected function getJsonRespnse(\MZ\PostmarkBundle\Postmark\Message $message) {
        return $message->send();
    }
    
    
}