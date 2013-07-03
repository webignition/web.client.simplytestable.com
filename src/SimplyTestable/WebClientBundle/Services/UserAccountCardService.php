<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;

class UserAccountCardService extends UserService {
    
    public function associate(User $user, $stripe_card_token) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
                $this->getUrl('user_card_associate', array(
                    'email' => $user->getUsername(),
                    'stripe_card_token' => $stripe_card_token
                ))
        );
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $response = $request->send();
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {            
            $response = $badResponseException->getResponse();
            
            if ($this->httpResponseHasStripeError($response)) {
                throw $this->getUserAccountCardExceptionFromHttpResponse($response);
            }
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            var_dump($curlException);
            exit();
            return $curlException->getErrorNo();
        }
    }
    
    
    /**
     * 
     * @param \Guzzle\Http\Message\Response $response
     * @return boolean
     */
    private function httpResponseHasStripeError(\Guzzle\Http\Message\Response $response) {
        return count($this->getStripeErrorValuesFromHttpResponse($response)) > 0;
    }
    
    
    /**
     * 
     * @param \Guzzle\Http\Message\Response $response
     * @return \SimplyTestable\WebClientBundle\Exception\UserAccountCardException
     */
    private function getUserAccountCardExceptionFromHttpResponse(\Guzzle\Http\Message\Response $response) { 
        $stripeErrorValues = $this->getStripeErrorValuesFromHttpResponse($response);
        
        $message = (isset($stripeErrorValues['message'])) ? $stripeErrorValues['message'] : '';
        $param = (isset($stripeErrorValues['param'])) ? $stripeErrorValues['param'] : '';
        $code = (isset($stripeErrorValues['code'])) ? $stripeErrorValues['code'] : '';
        
        return new UserAccountCardException($message, $param, $code);      
    }
    
    
    /**
     * 
     * @param \Guzzle\Http\Message\Response $response
     * @return array
     */
    private function getStripeErrorValuesFromHttpResponse(\Guzzle\Http\Message\Response $response) {
        $stripeErrorKeys = array('message', 'param', 'code');
        $stripeErrorValues = array();
        
        foreach ($stripeErrorKeys as $stripeErrorKeySuffix) {
            $stripeErrorKey = 'x-stripe-error-' . $stripeErrorKeySuffix;
            
            if ($response->hasHeader($stripeErrorKey)) {
                $errorHeaderValues = $response->getHeader($stripeErrorKey)->toArray();

                if (count($errorHeaderValues)) {
                    $stripeErrorValues[$stripeErrorKeySuffix] = $errorHeaderValues[0];
                }                
            }
        }        
        
        return $stripeErrorValues;       
    }
    
}